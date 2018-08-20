<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RemindReport extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'remind';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gui nhac nho bao cao cong viec hang ngay';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
        $dayOfTheWeek = Date('w');
        if ($dayOfTheWeek == '0' || $dayOfTheWeek =='6') {  // Sunday or Saturday
            Log::info('Khong gui nhac nho vao T7, CN');
            return;
        }

        $userModel = new User;
        $reportModel = new Report;
        $departmentModel = new Department;

        $today = Date('d-m-Y');
        $departments = $departmentModel->getListDepartments();
        foreach ($departments as $i => $department) {
            $lateUsers = $userModel->getLateUsers($today, $department->id);
            $rs = $reportModel->sendReminderEmail($today, $lateUsers);
            if ($rs === true) {
                Log::info('Da gui nhac nho ngay ' . $today);
                $this->info('Success');
            } else {
                $response['status'] = 'FAIL';
                $response['msg'] = $rs;
            }
        }

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return array(
		);
	}
}
