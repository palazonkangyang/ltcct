<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class SessionController extends Controller
{
    public static function forgetSession(){
      $forget_key_list = array('focus_devotee',
                          'xiaozai_same_focusdevotee',
                          'xiaozai_focusdevotee',
                          'xiaozai_different_family',
                          'xiaozai_setting_samefamily',
                          'xiaozai_same_family',
                          'xiaozai_setting_differentfamily',
                          'xiaozai_setting_samefamily_last1year',
                          'xiaozai_setting_differentfamily_last1year',
                          'xiaozai_receipts',
                          'searchfocus_devotee',
                          'focusdevotee_specialremarks',
                          'devotee_lists',
                          'xianyou_same_family',
                          'xianyou_same_focusdevotee',
                          'xianyou_different_family',
                          'ciji_same_family',
                          'ciji_same_focusdevotee',
                          'ciji_different_family',
                          'yuejuan_same_family',
                          'yuejuan_same_focusdevotee',
                          'yuejuan_different_family',
                          'setting_samefamily',
                          'xianyou_focusdevotee',
                          'setting_differentfamily',
                          'receipts',
                          'ciji_receipts',
                          'yuejuan_receipts',
                          'optionaladdresses',
                          'optionalvehicles',
                          'specialRemarks',
                          'focusdevotee_amount',
                          'samefamily_amount',
                          'differentfamily_amount',
                          'kongdan_same_family',
                          'kongdan_same_focusdevotee',
                          'kongdan_different_family',
                          'kongdan_setting_samefamily',
                          'kongdan_setting_differentfamily',
                          'kongdan_focusdevotee',
                          'kongdan_receipts',
                          'qifu_same_family',
                          'qifu_same_focusdevotee',
                          'qifu_different_family',
                          'qifu_setting_samefamily',
                          'qifu_setting_differentfamily',
                          'qifu_focusdevotee',
                          'qifu_receipts',
                          'kongdan_setting_differentfamily_last1year',
                          'kongdan_setting_differentfamily_last2year',
                          'kongdan_setting_differentfamily_last3year',
                          'kongdan_setting_differentfamily_last4year',
                          'kongdan_setting_differentfamily_last5year',
                          'kongdan_setting_samefamily_last1year',
                          'kongdan_setting_samefamily_last2year',
                          'kongdan_setting_samefamily_last3year',
                          'kongdan_setting_samefamily_last4year',
                          'kongdan_setting_samefamily_last5year',
                          'same_family_code',
                          'relative_and_friends',
                          'transaction'
                         );

      foreach(Session()->all() as $key => $value) {
        if(in_array($key,$forget_key_list))
        {
          Session()->forget($key);
        }
      }
    }
}
