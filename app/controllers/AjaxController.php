<?php

/**
 * Class AjaxController
 */
class AjaxController extends BaseController {
    private $project;
    private $user;
    private $report;
    private $dateOff;
    private $synToken;

    public function __construct(Project $project, User $user, Report $report, DateOff $dateOff, SynToken $synToken) {
        $this->project = $project;
        $this->user = $user;
        $this->report = $report;
        $this->dateOff = $dateOff;
        $this->synToken = $synToken;
    }

    public function logTask() {
        $response = array();
        if (Auth::check()) {
            $postData = Input::get();
            $rs = $this->report->logTask(Auth::id(), $postData);
            if ($rs === true) {
                $response['status'] = 'OK';
            } else {
                $response['status'] = 'FAIL';
                $response['msg'] = $rs;
            }
        } else {
            $response['status'] = 'FAIL';
            $response['msg'] = 'Chưa đăng nhập';
        }
        return Response::json($response);
    }

    public function getTasks($projectId, $date) {
        $response = array();
        if (Auth::check()) {
            $report = $this->project->getReportOfTheDate(Auth::id(), $projectId, $date);
            $response['status'] = 'OK';
            $response['report'] = $report;
        } else {
            $response['status'] = 'FAIL';
            $response['msg'] = 'Chưa đăng nhập';
        }
        return Response::json($response);
    }

    public function remind() {
        $response = array();
        $authUser = Auth::user();
        $roleArr = explode('|', $authUser->role);
        if (in_array('1', $roleArr)) {
            $postData = Input::get();
            $reportDate = $postData['reportDate'];
            $lateUsers = $this->user->getLateUsers($reportDate);
            $rs = $this->report->sendReminderEmail($reportDate, $lateUsers);
            if ($rs === true) {
                $response['status'] = 'OK';
            } else {
                $response['status'] = 'FAIL';
                $response['msg'] = $rs;
            }
        } else {
            $response['status'] = 'FAIL';
            $response['msg'] = 'Không đủ quyền';
        }

        return Response::json($response);
    }

    public function sendAllReport() {
        $response = array();
        $authUser = Auth::user();
        $roleArr = explode('|', $authUser->role);
        if (in_array('1', $roleArr)) {
            $postData = Input::get();
            $reportDate = $postData['reportDate'];
            $lateUsers = $this->user->getLateUsers($reportDate, $authUser->department_id);
            $allUsersOff = $this->dateOff->getAllDateOffNotConfirm($authUser->department_id);
            $reports = $this->report->synthesizeReport($reportDate, $authUser->department_id);
            $synToken = $this->synToken->generateToken();
            $employersOfDepartment = $this->user->getEmployersOfDepartment($authUser->department_id);
            $rs = $this->report->sendAllReportEmail($employersOfDepartment, $reports, $reportDate, $lateUsers, $allUsersOff, $synToken, $employersOfDepartment);
            if ($rs === true) {
                $response['status'] = 'OK';
            } else {
                $response['status'] = 'FAIL';
                $response['msg'] = $rs;
            }
        } else {
            $response['status'] = 'FAIL';
            $response['msg'] = 'Không đủ quyền';
        }

        return Response::json($response);
    }

    public function joinProject() {
        $response = array();
        $userId = Auth::id();
        $projectId = Input::get('projectId');
        $existed = DB::table('project_user')->where(array(
            'user_id' => $userId,
            'project_id' => $projectId
        ))->first();
        if (!empty($existed)) {
            $response['status'] = 'FAIL';
            $response['msg'] = 'Dự án đã tham gia';
        } else {
            $rs = DB::table('project_user')->insert(array(
                'user_id' => $userId,
                'project_id' => $projectId
            ));
            if ($rs) {
                $response['status'] = 'OK';
            } else {
                $response['status'] = 'FAIL';
                $response['msg'] = 'Không tham gia được dự án. Vui lòng thử lại sau';
            }
        }
        return Response::json($response);
    }

    public function leaveProject() {
        $response = array();
        $userId = Auth::id();
        $projectId = Input::get('projectId');
        $existed = DB::table('project_user')->where(array(
            'user_id' => $userId,
            'project_id' => $projectId
        ))->first();
        if (empty($existed)) {
            $response['status'] = 'FAIL';
            $response['msg'] = 'Chưa tham gia dự án';
        } else {
            $rs = DB::table('project_user')->where(array(
                'user_id' => $userId,
                'project_id' => $projectId
            ))->delete();
            if ($rs) {
                $response['status'] = 'OK';
            } else {
                $response['status'] = 'FAIL';
                $response['msg'] = 'Không rời được dự án. Vui lòng thử lại sau';
            }
        }
        return Response::json($response);
    }

    public function getDatesOff($month) {
        $response = array();
        $currentYear = Date('Y');

        $datesOff = $this->dateOff->getDatesOff(Auth::id(), $month, $currentYear);
        if (is_array($datesOff)) {
            $response['status'] = 'OK';
            $response['dates'] = $datesOff;
        } else {
            $response['status'] = 'FAIL';
            $response['msg'] = $datesOff;
        }
        return Response::json($response);
    }

    public function addDateOff() {
        $postData = Input::get();
        $rs = $this->dateOff->addDateOff(Auth::id(), $postData['date'], $postData['month'], $postData['reason']);
        if ($rs === true) {
            $response['status'] = 'OK';
        } else {
            $response['status'] = 'FAIL';
            $response['msg'] = $rs;
        }
        return Response::json($response);
    }

    public function removeDateOff() {
        $postData = Input::get();
        $rs = $this->dateOff->removeDateOff(Auth::id(), $postData['date'], $postData['month']);
        if ($rs === true) {
            $response['status'] = 'OK';
        } else {
            $response['status'] = 'FAIL';
            $response['msg'] = $rs;
        }
        return Response::json($response);
    }
}