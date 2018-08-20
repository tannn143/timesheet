<?php

class Department extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'departments';

    /**
     * Lấy danh sách các user tham gia dự án
     */
    public function users() {
        return $this->hasMany('User');
    }

    public function getListDepartments() {
        $departments = Department::all();
        return $departments;
    }

}
