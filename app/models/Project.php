<?php

class Project extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'projects';

    /**
     * Lấy danh sách các user tham gia dự án
     */
    public function users() {
        return $this->belongsToMany('User');
    }

    /**
     * Lấy danh sách các report của dự án
     */
    public function reports() {
        return $this->hasMany('Report');
    }

    public function getReportOfTheDate($userId, $projectId, $date) {
        $userId = trim($userId);
        $projectId = trim($projectId);
        $startOfTheDate = $date . ' 00:00:00';
        $endOfTheDate = $date . ' 23:59:59';

        $query = Report::where('project_id', '=', $projectId)
                       ->where('user_id', '=', $userId)
                       ->where('log_date', '>=', $startOfTheDate)
                       ->where('log_date', '<=', $endOfTheDate);
        $todayTask = $query->first();
        if (!empty($todayTask)) {
            return $todayTask->toArray();
        } else {
            return array();
        }
    }

    public function getProjectsInvolved($userId) {
        $allProjects = Project::all()->toArray();
        $user = User::find($userId);
        $involvedProjects = $user->projects->toArray();

        foreach ($allProjects as $i => $project) {
            $allProjects[$i]['involved'] = false;
            foreach ($involvedProjects as $j => $involveProject) {
                if ($project['id'] == $involveProject['id']) {
                    $allProjects[$i]['involved'] = true;
                    break;
                }
            }
        }

        return $allProjects;
    }
}
