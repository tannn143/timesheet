<?php

class SynToken extends Eloquent {
	protected $table = 'syn_token';

    public function generateToken() {
        $tokenStr = md5(Hash::make(Date('Y-m-d H:i:s')));
        $existToken = SynToken::where('token', '=', $tokenStr)->first();
        while (!empty($existToken)) {
            $tokenStr = md5(Hash::make(Date('Y-m-d H:i:s')));
            $existToken = SynToken::where('token', '=', $tokenStr)->first();
        }

        $token = new SynToken;
        $token->token = $tokenStr;
        $token->status = 0;
        if ($token->save()) {
            return $tokenStr;
        }
        return null;
    }

    public function isValidToken($tokenStr) {
        $token = SynToken::where('token', '=', $tokenStr)->first();
        if ($token && $token->status == 0) {
            return true;
        }
        return false;
    }

    public function setInvalidToken($tokenStr) {
        $token = SynToken::where('token', '=', $tokenStr)->first();
        if ($token) {
            $token->status = 1;
            return $token->save();
        }
        return false;
    }
}
