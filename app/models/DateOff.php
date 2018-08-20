<?php

class DateOff extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'reg_date_off';

    public function getAllDateOffNotConfirm($departmentId) {
        $allDateOffs = DB::table('reg_date_off')
            ->join('users', 'users.id', '=', 'reg_date_off.user_id')
            ->select('reg_date_off.id', 'reg_date_off.user_id', 'reg_date_off.reason', 'users.full_name', DB::raw('DATE_FORMAT(reg_date_off.date_off, "%d-%m-%Y") as date_off'))
            ->where('reg_date_off.status', '=', '0')
            ->where('users.department_id', '=', $departmentId)
            ->orderBy('reg_date_off.user_id', 'asc')
            ->orderBy('reg_date_off.date_off', 'asc')
            ->get();
        return $allDateOffs;
    }

    public function getDatesOff($userId, $month, $year) {
        $startDate = $year . '-' . $month . '-' . '01 00:00:00';
        $monthTimestamp = DateTime::createFromFormat('Y-m-d H:i:s', $startDate);
        $nDayInMonth = intval(Date('t', $monthTimestamp->getTimestamp()));
        $endDate = $year . '-' . $month . '-' . $nDayInMonth . ' 00:00:00';

        $datesOff = DateOff::where('user_id', '=', $userId)
            ->where('date_off', '>=', $startDate)
            ->where('date_off', '<=', $endDate)
            ->get();
        $datesOff = $datesOff->toArray();

        $tmp = array();
        for ($i=1; $i<=$nDayInMonth; $i++) {
            if (!isset($tmp[$i-1])) {
                $tmp[$i-1] = array(
                    'display' => Date('d/m/Y', DateTime::createFromFormat('Y-m-d H:i:s', $year . '-' . $month . '-' . $i .' 00:00:00')->getTimestamp()),
                    'reason' => '',
                    'is_off' => false
                );
            }
            foreach ($datesOff as $j => $date) {
                $d = DateTime::createFromFormat('Y-m-d H:i:s', $date['date_off']);
                $d = $d->getTimestamp();
                $d = intval(Date('d', $d));
                if ($i == $d) {
                    $date['is_off'] = true;
                    $date['display'] = Date('d/m/Y', DateTime::createFromFormat('Y-m-d H:i:s', $date['date_off'])->getTimestamp());
                    $tmp[$i-1] = $date;
                }
            }
        }
        return $tmp;
    }

    public function addDateOff($userId, $date, $month, $reason) {
        if (trim($reason) == '') {
            return 'Vui lòng nhập lý do nghỉ phép';
        }
        $currentYear = Date('Y');
        $dateStr = $currentYear . '-' . $month . '-' . $date . ' 00:00:00';
        $query = DateOff::where('user_id', '=', $userId)
            ->where('date_off', '=', $dateStr);
        $dateOff = $query->first();

        if (!empty($dateOff)) {
            $dateOff->user_id = $userId;
            $dateOff->reason = trim($reason);
            return $dateOff->save();
        } else {
            $newDateOff = new DateOff;
            $newDateOff->user_id = $userId;
            $newDateOff->date_off = $dateStr;
            $newDateOff->reason = trim($reason);
            return $newDateOff->save();
        }
    }

    public function removeDateoff($userId, $date, $month) {
        $currentYear = Date('Y');
        $dateStr = $currentYear . '-' . $month . '-' . $date . ' 00:00:00';
        $query = DateOff::where('user_id', '=', $userId)
            ->where('date_off', '=', $dateStr);
        $dateOff = $query->first();

        if (!empty($dateOff)) {
            return $dateOff->delete();
        } else {
            return 'Ngày phép không tồn tại';
        }
    }

    public function acceptAll() {
        return DB::table('reg_date_off')->where('status', 0)->update(array('status' => 1));
    }

    public function reject($dateOffId) {
        $dateOff = DateOff::find($dateOffId);
        if ($dateOff) {
            $dateOff->status = 2;
            return $dateOff->save();
        }
        return false;
    }
}
