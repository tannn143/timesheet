<?php

/**
 * Class HomeController
 * @property Project $project
 * @property Report $report
 * @property User $user
 */
class HomeController extends BaseController {
    private $project;
    private $report;
    private $user;
    private $dateOff;
    private $synToken;

    public function __construct(Project $project, Report $report, User $user, DateOff $dateOff, SynToken $synToken) {
        $this->project = $project;
        $this->report = $report;
        $this->user = $user;
        $this->dateOff = $dateOff;
        $this->synToken = $synToken;

        View::composer('*', function($view) {
            $view->with('user', Auth::user());
            $view->with('baseUrl', action('HomeController@logTask'));
        });
    }

    public function register() {
        $postData = Input::get();
        if (!empty($postData)) {
            $errors = array();
            $postData['username'] = trim($postData['username']);
            $postData['full_name'] = trim($postData['full_name']);
            $postData['email'] = trim($postData['email']);
            if (empty($postData['username'])) {
                $errors[] = 'Nhập thiếu tên đăng nhập';
            }

            if (empty($postData['email'])) {
                $errors[] = 'Nhập thiếu email';
            } else {
                $validator = Validator::make(
                    array('email' => $postData['email']),
                    array('email' => 'email')
                );
                if ($validator->fails()) {
                    $errors[] = 'Email không hợp lệ';
                }
            }

            if (empty($postData['full_name'])) {
                $errors[] = 'Nhập thiếu họ tên';
            }
            if (strlen($postData['password']) < 6) {
                $errors[] = 'Mật khẩu yêu cầu ít nhất 6 ký tự';
            } else {
                if ($postData['password'] != $postData['confirm_password']) {
                    $errors[] = 'Xác nhận mật khẩu không đúng';
                }
            }
            if (!empty($errors)) {
                return Redirect::to('register')->with(array(
                    'regErrors' => $errors,
                    'username' => $postData['username'],
                    'full_name' => $postData['full_name'],
                    'email' => $postData['email']
                ));
            }

            $rs = $this->user->register($postData['username'], $postData['full_name'], $postData['email'], $postData['password'], 1, 0);

            if (is_array($rs)) {
                return Redirect::to('/');
            } else {
                return Redirect::to('register')->with(array(
                    'regErrors' => array($rs),
                    'username' => $postData['username'],
                    'full_name' => $postData['full_name'],
                    'email' => $postData['email']
                ));
            }
        }
        return View::make('register');
    }

    /**
     * Login page + Handle login submit
     */
    public function login() {
        $postData = Input::get();
        if (!empty($postData)) {
            if (Auth::attempt(array('username' => $postData['username'], 'password' => $postData['password']))) {
                Log::info($postData['username'] . ' has logged in successfully.');
                return Redirect::to('/');
            } else {
                $ldapUser = $this->user->ldapAuthenticate($postData['username'], $postData['password']);
                if ($ldapUser !== false) {
                    Auth::loginUsingId($ldapUser['id']);
                    return Redirect::to('/');
                } else {
                    return Redirect::to('login');
                }
            }
        }
        return View::make('login')->with('baseUrl', action('HomeController@logTask'));;
    }

    /**
     * Logout
     */
    public function logout() {
        Auth::logout();
        return Redirect::to('/');
    }

    /**
     * Report daily task page
     */
    public function logTask() {
        if (isEmployee()) {
            $user = Auth::user();
            $projects = $user->projects;
            $report = null;
            if (count($projects) > 0) {
                $firstProject = $projects[0];
                $report = $this->project->getReportOfTheDate(Auth::id(), $firstProject->id, Date('Y-m-d'));
            }
            return View::make('logTask', array(
                'projects' => $projects,
                'report' => $report
            ));
        } else if (isEmployer()) {
            return Redirect::to('reports');
        }
	}

    public function registerDayOff() {
        if (isEmployee()) {
            $currentMonth = Date('m');
            $currentYear = Date('Y');

            $datesOffInCurrentMonth = $this->dateOff->getDatesOff(Auth::id(), $currentMonth, $currentYear);

            return View::make('register_day_off', array(
                'datesOff' => $datesOffInCurrentMonth,
                'currentMonth' => $currentMonth,
                'currentYear' => $currentYear
            ));
        }
    }

    public function registerProjects() {
        if (isEmployee()) {
            $projectsInvolved = $this->project->getProjectsInvolved(Auth::id());
            return View::make('register_projects', array(
                'projects' => $projectsInvolved
            ));
        }
    }

    public function synthesize($date = null) {
        if ($date == null) {
            $date = Date('d-m-Y');
        }
        $authUser = Auth::user();
        $lateUsers = $this->user->getLateUsers($date, $authUser->department_id);
        $usersOffInDate = $this->user->getUserOffOnDate($date, $authUser->department_id);
        $reports = $this->report->synthesizeReport($date, $authUser->department_id);

        return View::make('synthesize', array(
            'lateUsers' => $lateUsers,
            'usersOffInDate' => $usersOffInDate,
            'reportDate' => $date,
            'reports' => $reports
        ));
    }

    public function weekReports($year, $weekNumber = null) {
        if ($weekNumber == null) {
            $weekNumber = Date('W');
        }

        $first = firstDateOfTheWeek($weekNumber, $year);
        $last = lastDateOfTheWeek($weekNumber, $year);

        $reports = $this->report->getReportsBetween($first, $last);

        return View::make('week_reports', array(
            'weekNumber' => $weekNumber,
            'fromDate' => $first,
            'toDate' => $last,
            'reports' => $reports
        ));
    }

    public function history($date = null) {
        if (isEmployee()) {
            if ($date == null) {
                $date = Date('d-m-Y');
            }
            $reports = $this->report->synthesizeReportOfUser($date, Auth::id());
            return View::make('history', array(
                'reportDate' => $date,
                'reports' => $reports
            ));
        }
    }

    public function acceptAll($tokenStr) {
        if ($this->synToken->isValidToken($tokenStr)) {
            if ($this->dateOff->acceptAll()) {
                $this->synToken->setInvalidToken($tokenStr);
                die('OK. Da dong y tat ca cac yeu cau xin nghi phep (tru cac yeu cau khong duoc chap nhan)');
            } else {
                die('Khong co yeu cau nao duoc dong y');
            }
        } else {
            die('Hanh dong da thuc hien truoc do');
        }
    }

    public function reject($dateOffId, $tokenStr) {
        if ($this->synToken->isValidToken($tokenStr)) {
            if ($this->dateOff->reject($dateOffId)) {
                $this->synToken->setInvalidToken($tokenStr);
                die('OK. Yeu cau nghi phep da khong duoc dong y');
            } else {
                die('Khong co yeu cau nao bi tu choi');
            }
        } else {
            die('Hanh dong da thuc hien truoc do');
        }
    }

    public function assignReportDays() {
        $authUser = Auth::user();
        if (isEmployer()) {
            $employees = $this->user->getEmployeesOfDepartment($authUser->department_id);
            return View::make('assign_report_days', array(
                'employees' => $employees
            ));
        }

    }
}
