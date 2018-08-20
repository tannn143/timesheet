<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SynthesizeReport extends Command {
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'synthesize';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Tong hop bao cao cong viec trong ngay va gui cho lanh dao phong qua email';

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
        if ($dayOfTheWeek == '0' || $dayOfTheWeek == '6') {  // Sunday or Saturday
            Log::info('Khong gui tong hop vao T7, CN');
            return;
        }

        $userModel = new User;
        $reportModel = new Report;
        $dateOffModel = new DateOff;
        $synTokenModel = new SynToken;
        $departmentModel = new Department;

        $today = Date('d-m-Y');
        $departments = $departmentModel->getListDepartments();
        foreach ($departments as $i => $department) {
            $lateUsers = $userModel->getLateUsers($today, $department->id);
            $reports = $reportModel->synthesizeReport($today, $department->id);
            $allUsersOff = $dateOffModel->getAllDateOffNotConfirm($department->id);
            $synToken = $synTokenModel->generateToken();
            $employersOfDepartment = $userModel->getEmployersOfDepartment($department->id);
            $rs = $reportModel->sendAllReportEmail($employersOfDepartment, $reports, $today, $lateUsers, $allUsersOff, $synToken);
            if ($rs === true) {
                Log::info('Da gui tong hop bao cao ngay ' . $today . ' phong ' . $department->name);
                $this->info('Success');
            } else {
                Log::error('Khong gui duoc tong hop bao cao ngay ' . $today . ' phong ' . $department->name);
                Log::error($rs);
                $this->info('Fail');
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
