<?php

class Report extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'reports';

    /**
     * Lấy project report này thuộc vào
     */
    public function project() {
        return $this->belongsTo('Project');
    }

    /**
     * Lấy user tạo report này
     */
    public function user() {
        return $this->belongsTo('User');
    }

    /**
     * Ghi log công việc ngày
     * @param $data(project, currentTask, nextTask, notice)
     */
    public function logTask($userId, $data) {
        if (trim($data['currentTask']) == '') {
            return 'Nhập công việc ngày hôm nay';
        }
        if (!isset($data['project'])) {
            return 'Hãy tham gia dự án đang thực hiện';
        }

        // Thời gian gửi báo cáo
        $reportTime = DateTime::createFromFormat('Y-m-d H:i:s', Date('Y-m-d H:i:s'));

        // Thời gian tổng hợp ngày báo cáo
        $synthesizeTime = DateTime::createFromFormat('Y-m-d H:i:s', $data['mdate'] . ' ' . Config::get('app.synthesizeTime') . ':00');

        // Thời gian bắt đầu và kết thúc của ngày báo cáo
        $startOfTheDate = $data['mdate'] . ' 00:00:00';
        $endOfTheDate = $data['mdate'] . ' 23:59:59';


        $query = Report::where('project_id', '=', $data['project'])
                       ->where('user_id', '=', $userId)
                       ->where('log_date', '<=', $endOfTheDate)
                       ->where('log_date', '>=', $startOfTheDate);
        $reportOfTheDay = $query->first();

        if (!empty($reportOfTheDay)) {
            $reportOfTheDay->today_task = $data['currentTask'];
            $reportOfTheDay->next_task = $data['nextTask'];
            $reportOfTheDay->notice = $data['notice'];
            $reportOfTheDay->is_late = ($reportTime > $synthesizeTime) ? 1 : 0;
            $reportOfTheDay->log_date = $startOfTheDate;
            $reportOfTheDay->save();
            return true;
        } else {
            $newReport = new Report;
            $newReport->user_id = $userId;
            $newReport->project_id = $data['project'];
            $newReport->today_task = $data['currentTask'];
            $newReport->next_task = $data['nextTask'];
            $newReport->notice = $data['notice'];
            $newReport->is_late = ($reportTime > $synthesizeTime) ? 1 : 0;
            $newReport->log_date = $startOfTheDate;
            $newReport->save();
            return true;
        }
    }

    /**
     * Tổng hợp báo cáo công việc theo ngày
     * @param date Y-m-d
     */
    public function synthesizeReport($date, $departmentId) {
        $date = DateTime::createFromFormat('d-m-Y', $date);
        $output = $date->format('Y-m-d');

        $query = DB::table('reports')
            ->select('reports.*', 'projects.name as project_name', 'users.full_name as user_full_name')
            ->join('users', 'reports.user_id', '=', 'users.id')
            ->join('projects', 'reports.project_id', '=', 'projects.id')
            ->where('users.department_id', '=', $departmentId)
            ->where('reports.log_date', '=', $output . ' 00:00:00')
            ->orderBy('reports.project_id', 'asc')
            ->orderBy('reports.user_id', 'asc');
        $reports = $query->get();
        foreach ($reports as $i => $report) {
            $reports[$i]['project'] = array(
                'name' => $report['project_name']
            );
            $reports[$i]['user'] = array(
                'full_name' => $report['user_full_name']
            );
        }
        return $reports;
    }

    public function getReportsBetween($fromDate, $toDate) {
        $fromDate = DateTime::createFromFormat('d-m-Y', $fromDate);
        $fromDate = $fromDate->format('Y-m-d');

        $toDate = DateTime::createFromFormat('d-m-Y', $toDate);
        $toDate = $toDate->format('Y-m-d');

        $query = Report::where('log_date', '>=', $fromDate . ' 00:00:00')
            ->where('log_date', '<=', $toDate . ' 00:00:00')
            ->orderBy('project_id', 'asc')
            ->orderBy('user_id', 'asc');
        return $query->with('project')->with('user')->get();
    }

    /**
     * Tổng hợp báo cáo công việc theo ngày của user cụ thể
     * @param date Y-m-d
     * @param userId
     */
    public function synthesizeReportOfUser($date, $userId) {
        $date = DateTime::createFromFormat('d-m-Y', $date);
        $output = $date->format('Y-m-d');

        $query = Report::where('log_date', '=', $output . ' 00:00:00')
            ->where('user_id', '=', $userId)
            ->orderBy('project_id', 'asc')
            ->orderBy('user_id', 'asc');
        return $query->with('project')->with('user')->get();
    }

    /**
     * Gửi email nhắc nhở mọi người báo cáo công việc theo ngày
     * @param $reportDate
     * @param $lateUsers
     */
    public function sendReminderEmail($reportDate, $lateUsers) {
        if (empty($lateUsers)) {
            return true;
        }
        try {
            Mail::send(
                'emails.report_reminder',
                array(
                    'reportDate' => $reportDate,
                    'lateUsers' => $lateUsers,
                    'url' => Config::get('app.urlUsingOnlyInMail')
                ),
                function($message) use ($reportDate, $lateUsers) {
                    $message->subject('[BCCV] Nhắc nhở báo cáo công việc ngày ' . $reportDate);
                    foreach ($lateUsers as $i => $user) {
                        $message->to($user['email']);
                    }
                }
            );
        } catch (Exception $e) {
            return 'Không gửi được. Hãy thử lại sau.';
        }
        return true;
    }

    public function sendAllReportEmail($employersOfDepartment, $reports, $reportDate, $lateUsers, $allUsersOff, $synToken) {
        try {
            $synTokenModel = new SynToken;
            foreach ($allUsersOff as $i => $u) {
                $newTokenForReject = $synTokenModel->generateToken();
                $allUsersOff[$i]['rejectUrl'] = Config::get('app.urlUsingOnlyInMail') . '/public/reject/' . $allUsersOff[$i]['id'] . '/' . $newTokenForReject;
            }
            $departmentName = null;
            if (!empty($employersOfDepartment)) {
                $departmentName = $employersOfDepartment['0']['department_name'];
            }
            Mail::send(
                'emails.synthesize',
                array(
                    'departmentName' => $departmentName,
                    'reports' => $reports,
                    'reportDate' => $reportDate,
                    'lateUsers' => $lateUsers,
                    'allUsersOff' => $allUsersOff,
                    'url' => Config::get('app.urlUsingOnlyInMail'),
                    'acceptAllUrl' => Config::get('app.urlUsingOnlyInMail') . '/public/acceptAll/' . $synToken
                ),
                function($message) use ($reportDate, $lateUsers, $employersOfDepartment, $departmentName) {
                    $subject = '[BCCV] Tổng hợp công việc phòng ngày ' . $reportDate;
                    if ($departmentName != null) {
                        $subject = '[BCCV] Tổng hợp công việc ngày ' . $reportDate . ' phòng ' . $departmentName;
                    }
                    $message->subject($subject);
                    foreach ($employersOfDepartment as $i => $employer) {
                        $message->to($employer['email']);
                    }
                    $message->bcc('tannn@mobifone.vn');
                }
            );
        } catch (Exception $e) {
            return 'Không gửi được. Hãy thử lại sau.' . $e->getMessage();
        }
        return true;
    }
}
