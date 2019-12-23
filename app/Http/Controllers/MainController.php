<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Tau;
use App\chuyendi;

class MainController extends Controller
{
  public function signUp (Request $request) {
  $user = new Users();
  $rq = $request->json()->all();

  $user->TenKH = $rq['TenKH'];
  $user->password = $rq['password'];
  $user->Gmail = $rq['Gmail'];
  $user->save();
  return "SUCCESS";
}
public function signIn(Request $request ) {
  $rq = $request->json()->all();
  $user = Users::where('TenKH',$rq['TenKH'])->get()->toArray();
 if($user[0]['password'] == $rq['password']) {
    return $user[0]['ID'];
 }
else {
   return "FAIL";
 }
}
public function LayThongTinUser(Request $request) {
  $rq = $request->json()->all();

  $result = array();
  $user = Users::find($rq['IDUser']);
  $tau = Tau::where('IdUser',$rq['IDUser'])->get()->toArray();
  // var_dump($tau);
  $result['TenKH'] = $user->TenKH;
  $result['SLtau'] = count($tau);
  $result = json_encode($result);
  return $result;
}
public function AddBoat(Request $request) {
  $tau = new Tau();
  $rq = $request->json()->all();
  $tau->IDUser = $rq['IDUser'];
  $tau->TenTau = $rq['TenTau'];
  $tau->ChieuCao = $rq['ChieuCao'];
  $tau->ChieuRong = $rq['ChieuRong'];
  $tau->CanNangKhongTai = $rq['CanNangKhongTai'];
  $tau->HinhDangDayTau = $rq['HinhDangDayTau'];


  $tau->ViTriX = '0';
  $tau->ViTriY = '0';
  $tau->TrangThai = '0';
  $tau->save();
  return "SUCCESS";

}
public function ListBoat(Request $request) {
  $rq = $request->json()->all();

  $tau = Tau::where('IdUser',$rq['IDUser'])->get()->toArray();
  $result = json_encode($tau);
  return $result;
}
public function ChiTietTau(Request $request) {
  //test cần xem xét lại
  $rq = $request->json()->all();

  $antoan = array();
  $tau = Tau::find($rq['IdTau']);
  $chuyendi = $rq['IDChuyenDi'];
  if ($chuyendi) {
    foreach ($chuyendi as $key => $value) {
      $chuyendi = chuyendi::find($value);
      $antoan[] = $chuyendi->AnToan;
    }
  }
  $result = json_encode($antoan);
  return $result;
}
public function TinhKhoangCachTauDenCau($ViTriX,$ViTriY,$KinhDo,$ViDo) {
  $xDistance = abs($ViTriX-$KinhDo);
  $yDistance = abs($ViTriY-$ViDo);
  switch ($ViDo) {
    case ($ViDo < 15 && $vido >= 0):
      $mPerX = 111320;
      $mPerY = 110574;
      break;
    case ($ViDo < 30 && $vido >= 15):
      $mPerX = 107551;
      $mPerY = 110649;
      break;
    case ($ViDo < 45 && $vido >= 30):
      $mPerX = 96486;
      $mPerY = 110852;
      break;
    case ($ViDo < 60 && $vido >= 45):
      $mPerX = 78847;
      $mPerY = 111132;
      break;
    case ($ViDo < 75 && $vido >= 60):
      $mPerX = 55800;
      $mPerY = 111412;
      break;
    case ($ViDo < 90 && $vido >= 75):
      $mPerX = 28902;
      $mPerY = 111618;
      break;
    case (90):
      $mPerX = 0;
      $mPerY = 111694;
      break;
      $xTrueDistance = $xDistance*$mPerX;
      $yTrueDistance = $yDistance*$mPerY;
      $distance = sqrt(pow($xTrueDistance,2)+pow($yTrueDistance,2));
      return $distance;
  }
}
}
