<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    /**
     * Lấy danh sách project user tham gia
     */
    public function projects() {
        return $this->belongsToMany('Project');
    }

    /**
     * Lấy danh sách tất cả các report của user
     */
    public function reports() {
        return $this->hasMany('Report');
    }

    /**
     * Lấy danh sách tất cả các khoảng ngày nghỉ của user
     */
    public function dayLeave() {
        return $this->hasMany('DayLeave');
    }

    public function ldapAuthenticate($usernameOrEmail, $password) {
        // Trim data
        $usernameOrEmail = trim($usernameOrEmail);
        if (empty($usernameOrEmail) || empty($password)) {
            return false;
        }

        // Is username
        if (strpos($usernameOrEmail, '@mobifone.vn') === false) {
            $username = $usernameOrEmail;
            $email = $usernameOrEmail . '@mobifone.vn';
        }
        // Is email
        else {
            $username = str_replace('@mobifone.vn', '', $usernameOrEmail);
            $email = $usernameOrEmail;
        }

        $ldapServer = Config::get('app.ldapServer');
        $ldap = ldap_connect($ldapServer);

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldap, $email, $password);

        if ($bind) {
            @ldap_close($ldap);
            $existedUser = $this->where('username', '=', $username)->first();
            if (empty($existedUser)) {
                $existedUser = $this->register($username, $username, $email, $password, 1, 0);
            }
            return $existedUser;
        }
        return false;
    }

    public function getLateUsers($date, $departmentId) {
        $date = DateTime::createFromFormat('d-m-Y', $date);
        $date = $date->format('Y-m-d') . ' 00:00:00';

        $query = User::where('role', 'like', '%0%')
            ->where('department_id', '=', $departmentId)
            ->whereRaw('(select count(*) from reports where reports.user_id = users.id and log_date = \''. $date .'\') = 0')
            ->whereRaw('(select count(*) from reg_date_off where reg_date_off.user_id = users.id and date_off = \''. $date .'\') = 0');
        return $query->get()->toArray();
    }

    public function getUserOffOnDate($date, $departmentId) {
        $date = DateTime::createFromFormat('d-m-Y', $date);
        $date = $date->format('Y-m-d') . ' 00:00:00';

        $query = User::where('role', 'like', '%0%')
            ->where('department_id', '=', $departmentId)
            ->whereRaw('(select count(*) from reg_date_off where reg_date_off.user_id = users.id and date_off = \''. $date .'\') > 0');
        return $query->get()->toArray();
    }

    public function register($username, $fullName, $email, $password, $departmentId, $role) {
        $existedUser = $this->where('username', '=', $username)->first();
        if (!empty($existedUser)) {
            return 'Tên đăng nhập đã tồn tại';
        }

        $user = new User;
        $user->username = $username;
        $user->full_name = $fullName;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->department_id = $departmentId;
        $user->role = $role;
        $user->save();

        return $user->toArray();
    }

    public function getEmployersOfDepartment($departmentId) {
        $employers = User::where('role', 'like', '%1%')
            ->select('users.*', 'departments.name as department_name')
            ->join('departments', 'departments.id', '=', 'users.department_id')
            ->where('users.department_id', '=', $departmentId)
            ->get();
        return $employers->toArray();
    }

    public function getEmployeesOfDepartment($departmentId) {
            $employees = User::where('role', 'like', '%0%')
                ->select('users.*', 'departments.name as department_name')
                ->join('departments', 'departments.id', '=', 'users.department_id')
                ->where('users.department_id', '=', $departmentId)
                ->get();
            return $employees->toArray();
        }
}
