<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Wisata;
use App\Models\Rating;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller{
//menampilkan semua data yg sudah dirating

    public function recArroundWisata(Request $request) {
        $city = $request->city;
        $username = $request->username;
        $ratedUser = Rating::where('username', $username)->get();

        if($ratedUser->count() < 2) {
            return response()->json([
                "count" => $ratedUser->count(),
                "username" => $username,
                "message"=>"User must submit at least 2 rate"
            ], 400);
        }

        $ratedCurrentUser = [];
        $ratedOtherUser = [];
        $i = 0;

        foreach ($ratedUser as $rtuser){
            $idWisata = $rtuser->id_wisata;
            $username = $rtuser->username;
            $ratedOtherUser[$i] = Rating::where('id_wisata', $idWisata)->get();
            $ratedCurrentUser[$i] = Rating::where('id_wisata', $idWisata)->where('username', $username)->get();
            $i++;
        }

        $sum = [];
        $sumCurrentUser = [];
        $currentRate = [];
        $currentRateUser = [];

        for ($a=0; $a<count($ratedOtherUser); $a++) {
            $rate = $ratedOtherUser[$a];
            $rateCurrentUser = $ratedCurrentUser[$a];
            $rating = [];
            $ratingCurrentUser = [];
            for ($b = 0; $b<count($rate); $b++){
                $rating[$b] = $rate[$b]->angka_rating;
                $sum[$b] = 0;
            }

            for ($d=0; $d<count($rateCurrentUser); $d++) {
                $ratingCurrentUser[$d] = $rateCurrentUser[$d]->angka_rating;
                $sumCurrentUser[$d] = 0;
            }

            $currentRate[$a] = $rating;
            $currentRateUser[$a] = $ratingCurrentUser;
        }

        for ($x=0; $x<count($currentRate); $x++) {
            $sum = array_map(function (...$arrays) {
                return array_sum($arrays);
            }, $sum, $currentRate[$x]);

            $sumCurrentUser = array_map(function (...$arrays) {
                return array_sum($arrays);
            }, $sumCurrentUser, $currentRateUser[$x]);
        }
        //mencari rata-rata
        $average = [];
        $averageCurrentUser = [];
        for ($y=0; $y<count($sum); $y++) {
            $average[$y] = $sum[$y] / count($currentRate);
        }

        for ($z=0; $z<count($sumCurrentUser); $z++) {
            $averageCurrentUser[$z] = $sumCurrentUser[$z] / count($currentRateUser);
        }

        //Revisi
        $users = Users::get();
        $averageUsersRating = [];
        foreach($users as $key1 => $user){
            $ratWisatasUser = Rating::where('username', $user->username)->get();
            $sumUserRating = 0;
            $lengthUserRating = 1;

            foreach($ratWisatasUser as $key2 => $rhs){
                $sumUserRating = $sumUserRating + $rhs->angka_rating;
                $lengthUserRating++;
            }

            $averageUserRating = $sumUserRating / $lengthUserRating;
            $averageUsersRating[$key1] = $averageUserRating;
        }

        $usersCurrent = Users::where('username', $username)->get();
        $averageUsersCurrentRating = [];
        foreach($usersCurrent as $key3 => $user){
            $ratWisatasUserCurrent = Rating::where('username', $user->username)->get();
            $sumUserCurrentRating = 0;
            $lengthUserCurrentRating = 1;

            foreach($ratWisatasUserCurrent as $key4 => $rhs){
                $sumUserCurrentRating = $sumUserCurrentRating + $rhs->angka_rating;
                $lengthUserCurrentRating++;
            }

            $averageUserCurrentRating = $sumUserCurrentRating / $lengthUserCurrentRating;
            $averageUsersCurrentRating[$key1] = $averageUserCurrentRating;
        }

        //menampilkan hasil penyebut
        $currentRateAfterDiff = [];
        $currentRateUserAfterDiff = [];
        for ($c=0; $c<count($currentRate); $c++){
            $currentRateAfterDiff[$c] = array_map(function ($array1, $array2) { return $array1-$array2; } , $currentRate[$c], $average);
        }

        for ($f=0; $f<count($currentRateUser); $f++){
            $currentRateUserAfterDiff[$f] = array_map(function ($array1, $array2) { return $array1-$array2; } , $currentRateUser[$f], $averageCurrentUser);
        }

        //PEMBILANG
        $pembilang = [];
        for($g=0; $g<count($currentRateAfterDiff); $g++){
            $ll = $currentRateAfterDiff[$g];
            $lluser = $currentRateUserAfterDiff[$g];
            $llusers = 0;
            $items = [];

            for($k=0; $k<count($lluser); $k++) {
                $llusers = $lluser[$k];
            }

            for($h=0; $h<count($ll); $h++) {
                $lll = $ll[$h] * $llusers;
                $items[$h] = $lll;
            }

            $pembilang[$g] = $items;
        }

        $sumPembilang = [];
        for($l=0; $l<count($pembilang); $l++) {
            $sumPembilang = array_map(function (...$arrays) {
                return array_sum($arrays);
            }, $sumPembilang, $pembilang[$l]);
        }

        $pembilangAkhir = $sumPembilang;

        //PENYEBUT
        $penyebut = [];
        $penyebutUser = [];

        for($m=0; $m<count($currentRateAfterDiff); $m++) {
            $kk = $currentRateAfterDiff[$m];
            $items = [];
            for($n=0; $n<count($kk); $n++) {
                $kkk = $kk[$n];
                $items[$n] = $kkk * $kkk;
            }

            $penyebut[$m] = $items;
        }

        for($m=0; $m<count($currentRateUserAfterDiff); $m++) {
            $kk = $currentRateUserAfterDiff[$m];
            $items = [];
            for($n=0; $n<count($kk); $n++) {
                $kkk = $kk[$n];
                $items[$n] = $kkk * $kkk;
            }

            $penyebutUser[$m] = $items;
        }

        $sumPenyebut = [];
        for($o=0; $o<count($penyebut); $o++) {
            $sumPenyebut = array_map(function (...$arrays) {
                return array_sum($arrays);
            }, $sumPenyebut, $penyebut[$o]);
        }

        $sumPenyebutUser = [];
        for($o=0; $o<count($penyebutUser); $o++) {
            $sumPenyebutUser = array_map(function (...$arrays) {
                return array_sum($arrays);
            }, $sumPenyebutUser, $penyebutUser[$o]);
        }

        $sumPenyebutSQRT = [];
        for($p=0; $p<count($sumPenyebut); $p++) {
            $sumPenyebutSQRT[$p] = sqrt($sumPenyebut[$p]);
        }

        $sumPenyebutUserSQRT = [];
        for($p=0; $p<count($sumPenyebutUser); $p++) {
            $sumPenyebutUserSQRT[$p] = sqrt($sumPenyebutUser[$p]);
        }

        $penyebutAkhir = [];
        for($q=0; $q<count($sumPenyebutSQRT); $q++) {
            $mm = $sumPenyebutSQRT[$q];
            $nn = $sumPenyebutUserSQRT[0];
            $penyebutAkhir[$q] = $mm * $nn;
        }

        //HASIL
        $lengthPembilang = count($pembilangAkhir);
        $lengthPenyebut = count($penyebutAkhir);
        $result = [];

        if($lengthPembilang == $lengthPenyebut){
            for($r=0; $r<$lengthPembilang; $r++){
                $result[$r] = $pembilangAkhir[$r] / $penyebutAkhir[$r];
            }
        }

        $maxValue = max($result);
        $maxIndex = array_keys($result, max($result));

        $result_diff = [];
        $u = 0;
        for($t=0; $t<count($result); $t++) {
            if($t != $maxIndex[0]){
                $result_diff[$u] = $result[$t];
                $u++;
            }
        }

        $maxIndexResult = array_keys($result_diff, max($result_diff));

        $suggestWisata = [];
        for($s=0; $s<count($ratedOtherUser); $s++) {
            $rr = $ratedOtherUser[$s];
            $res = $rr[$maxIndex[0]];
            $suggestWisata[$s] = $res;
        }

        //Mencari Similaritas User
        $usernameSimilar = $suggestWisata[0]->username;
        $userData = Users::where('username', $usernameSimilar)->first();

        // $wisataSimilar = Rating::where('username', $usernameSimilar)->get();

        // $wisatas = [];
        // for($v=0; $v<count($ratedUser); $v++) {
        //     $wisata = [];
        //     for($w=0; $w<count($wisataSimilar); $w++) {
        //         if($wisataSimilar[$w]->id_wisata != $ratedUser[$v]->id_wisata){
        //             $wisata[$w] = $wisataSimilar[$w]->id_wisata;
        //         }
        //     }
        //     $wisatas[$v] = $wisata;
        // }

        $ratSimiliar = Rating::where('username', $usernameSimilar)->get();
        $ratUser = Rating::where('username', $username)->get();

        //Sorting berdasarkan rating tertinggi
        $unsortedData = collect($ratSimiliar);
        $ar = $unsortedData->sortByDesc('angka_rating');
        $ar = $ar->values()->toArray();

        $unsortedData2 = collect($ratUser);
        $ar2 = $unsortedData2->sortByDesc('angka_rating');
        $ar2 = $ar2->values()->toArray();

        $ratSimiliar = $ar;
        $ratUser = $ar2;

        $wisataSimilar = [];
        $wisataUser = [];

        foreach($ratUser as $key1 => $rtu) {
            $wisataUser[$key1] = $rtu['id_wisata'];
        }

        foreach($ratSimiliar as $key2 => $rts) {
            $wisataSimilar[$key2] = $rts['id_wisata'];
        }

        $wisata = array_values(array_diff($wisataSimilar, $wisataUser));
        $wisataDetails =[];

        for($zz=0; $zz<count($wisata); $zz++){
            $wisataDetails[$zz] = Wisata::where('id_wisata', $wisata[$zz])->first();
        }

        $wisataInCity = [];
        $cc = 0;
        foreach($wisataDetails as $key => $detail) {
            if($detail->kota == $city) {
                $wisataInCity[$cc] = $detail;
                $cc++;
            }
        }

        if(count($wisataInCity) < 1) {
            $wisataRatOwn = Rating::where('username', $username)->get();
            $wisataRatOwn = collect($wisataRatOwn);
            $wisataRatOwn = $wisataRatOwn->sortBy('angka_rating');
            $wisataRatOwn = $wisataRatOwn->values()->toArray();

            $wisataList = [];

            foreach($wisataRatOwn as $key => $htrt) {
                $wisataList[$key] = Wisata::where('id_wisata', $htrt['id_wisata'])->first();
            }

            $wisataInCity = $wisataList;
        }

        // return response()->json([
        //     "wisata_rec" => $wisataInCity,
        //     "user_similar" => $userData,
        //     "result_diff" => $result,
        //     "max_index" => $maxIndex
        // ], 200);
    }

    public function showOther(Request $request){
        $idWisata = $request->id_wisata;
        $gambarWisata = $request->gambar_wisata;
        $rating = Rating::where('id_wisata', $idWisata)->get();
        $ratAverage = 0;

        if($rating->count() > 0){
            $ratSum = 0;

            foreach($rating as $rat){
                $ratSum = $ratSum + $rat->angka_rating;
            }

            $ratAverage = $ratSum/$rating->count();
        }

        $gambarWisata = Storage::url($gambarWisata);

        return response()->json([
            "rating" => $ratAverage,
            "gambar" => $gambarWisata
        ], 200);
    }

    public function newAlgorithm(Request $request) {
        $username = $request->username;
        $city = $request->city;
        $wisataRatingUser = Rating::where('username', $username)->get();

        // cek berapa banyak wisata dirating user
        // jika kurang 2 maka rekomendasikan, wisata dgn rating tertinggi
        if ($wisataRatingUser->count() < 2) {
            // $wisataInCity = Wisata::select('wisata.*', DB::RAW('AVG(rating.angka_rating) as rating'))
            //                 ->leftJoin('rating', 'wisata.id_wisata', '=', 'rating.id_wisata')
            //                 ->groupBy('wisata.id_wisata')->orderBy('rating', 'desc')->limit(10)->get();
            
            $select = DB::select(DB::raw('select wisata.*, AVG(rating.angka_rating) as rating from `wisata` left join `rating` on `wisata`.`id_wisata` = `rating`.`id_wisata` group by `wisata`.`id_wisata` order by `rating` desc limit 10'));
            dd($select);
            
            return response()->json([
                "wisata_rec" => $wisataInCity,
                "username_similar" => null,
                "city" => $city
            ], 200);
        }

        //Rating wisata yang sudah dirating oleh user
        $wisataRatingAllUserByCurrentUser = [];
        $wisataRatingCurrentUserByCurrentUser = [];

        $usernamesByWisata = [];
        $usernamesByWisataCurrentUser = [];

        foreach($wisataRatingUser as $key => $hru){
            $idWisata = $hru->id_wisata;

            $rating = Rating::where('id_wisata', $idWisata)->get();
            $ratingCurrentUser = Rating::where('id_wisata', $idWisata)->where('username', $username)->get();

            $ratingWisata = [];
            $ratingWisataCurrentUser = [];

            $usernames = [];
            $usernamesCurrentUser = [];

            foreach($rating as $key2 => $rat){
                $ratingWisata[$key2] = $rat->angka_rating;
                $usernames[$key2] = $rat->username;
            }

            foreach($ratingCurrentUser as $key3 => $rat){
                $ratingWisataCurrentUser[$key3] = $rat->angka_rating;
                $usernamesCurrentUser[$key3] = $rat->username;
            }

            $wisataRatingAllUserByCurrentUser[$key] = $ratingWisata;
            $usernamesByWisata[$key] = $usernames;

            $wisataRatingCurrentUserByCurrentUser[$key] = $ratingWisataCurrentUser;
            $usernamesByWisataCurrentUser[$key] = $usernamesCurrentUser;
        }

        //Filter berdasakan user yang sama
        $arrayIntersect = $usernamesByWisata[0];
        for($i=1; $i<count($usernamesByWisata); $i++) {
            $usernamePerWisata = $usernamesByWisata[$i];
            $arrayIntersect = array_values(array_intersect($arrayIntersect, $usernamePerWisata));
        }

        $usernamesByWisataTemp = [];
        foreach($usernamesByWisata as $key6 => $ubh){
            $usernamesByWisataTemp[$key6] = array_values(array_intersect($arrayIntersect, $ubh));
        }

        $usernamesByWisata = $usernamesByWisataTemp;

        $wisataRatingAllUserByCurrentUserTemp = [];
        //Filter Rating berdasarkan user yang sama
        foreach($usernamesByWisata as $key7 =>$ubh){
            $usernames = $ubh;
            $ratings = [];
            for($i=0; $i<count($usernames); $i++){
                $ratings[$i] = Rating::where('username', $usernames[$i])->where('id_wisata', $wisataRatingUser[$key7]->id_wisata)->value('angka_rating');
            }
            $wisataRatingAllUserByCurrentUserTemp[$key7] = $ratings;
        }

        $wisataRatingAllUserByCurrentUser = $wisataRatingAllUserByCurrentUserTemp;

        //Rata-rata rating wisata yang sudah dirating oleh user
        $averageUsersRatingByWisata = [];
        for($i=0; $i<count($usernamesByWisata); $i++) {
            $userPerWisata = $usernamesByWisata[$i];
            $averageUsersRating = [];
            foreach($userPerWisata as $key => $username) {
                $userRating = Rating::where('username', $username)->get();
                $sumUserRating = 0;
                $lengthUserRating = 1;
                foreach($userRating as $key2 => $userRat) {
                    $sumUserRating += $userRat->angka_rating;
                    $lengthUserRating++;
                }

                $averageUsersRating[$key] = $sumUserRating / $lengthUserRating;
            }

            $averageUsersRatingByWisata[$i] = $averageUsersRating;
        }

        //Rata-rata rating semua wisata yang user ditanyakan
        $averageCurrentUserRatingByWisata = [];
        for($i=0; $i<count($usernamesByWisataCurrentUser); $i++) {
            $userPerWisata = $usernamesByWisataCurrentUser[$i];
            $averageCurrentUserRating = [];
            foreach($userPerWisata as $key => $username) {
                $userRating = Rating::where('username', $username)->get();
                $sumUserRating = 0;
                $lengthUserRating = 1;
                foreach($userRating as $key2 => $userRat) {
                    $sumUserRating += $userRat->angka_rating;
                    $lengthUserRating++;
                }

                $averageCurrentUserRating[$key] = $sumUserRating / $lengthUserRating;
            }

            $averageCurrentUserRatingByWisata[$i] = $averageCurrentUserRating;
        }

        $wisataLength = count($wisataRatingAllUserByCurrentUser);

        //Pengurangan rating dengan rata-rata
        $diffRatingUsersByWisata = [];
        for($i=0; $i<$wisataLength; $i++){
            $rating = $wisataRatingAllUserByCurrentUser[$i];
            $average = $averageUsersRatingByWisata[$i];
            $diffRatingUsersByWisata[$i] = array_map(function ($array1, $array2) { return $array1-$array2; } , $rating, $average);
        }

        //Pengurangan current user rating dengan rata-rata
        $diffRatingCurrentUserByWisata = [];
        for($i=0; $i<$wisataLength; $i++){
            $rating = $wisataRatingCurrentUserByCurrentUser[$i];
            $average = $averageCurrentUserRatingByWisata[$i];
            $diffRatingCurrentUserByWisata[$i] = array_map(function ($array1, $array2) { return $array1-$array2; } , $rating, $average);
        }

        //Perkalian dengan current user
        $multipleDiffRatingUsersByWisata = [];
        for($i=0; $i<$wisataLength; $i++){
            $users = $diffRatingUsersByWisata[$i];
            $currentUser = $diffRatingCurrentUserByWisata[$i];

            $multipleDiffRatingUserByWisata = [];
            foreach($users as $key4 => $user) {
                $multipleDiffRatingUserByWisata[$key4] = $user * $currentUser[0];
            }

            $multipleDiffRatingUsersByWisata[$i] = $multipleDiffRatingUserByWisata;
        }

        $sumMultipleDiffRatingUsersByWisata = $multipleDiffRatingUsersByWisata[0];
        foreach($multipleDiffRatingUsersByWisata as $mdrubh){
            $sumMultipleDiffRatingUsersByWisata = array_map(function (...$arrays) {
                return array_sum($arrays);
            }, $sumMultipleDiffRatingUsersByWisata, $mdrubh);
        }

        //Pembilang
        $pembilang = $sumMultipleDiffRatingUsersByWisata;

        //Dikuadratkan hasil pengurangan
        $expDiffRatingUsersByWisata = [];
        foreach($diffRatingUsersByWisata as $key9 => $drubh) {
            $ratings = $drubh;
            $expDiffRatingUserByWisata = [];
            foreach($ratings as $key10 => $rating){
                $expDiffRatingUserByWisata[$key10] = $rating * $rating;
            }

            $expDiffRatingUsersByWisata[$key9] = $expDiffRatingUserByWisata;
        }

        $expDiffRatingCurrentUsersByWisata = [];
        foreach($diffRatingCurrentUserByWisata as $key11 => $drcubh){
            $ratings = $drcubh;
            $expDiffRatingCurrentUserByWisata = [];
            foreach($ratings as $key12 => $rating){
                $expDiffRatingCurrentUserByWisata[$key12] = $rating * $rating;
            }

            $expDiffRatingCurrentUsersByWisata[$key11] = $expDiffRatingCurrentUserByWisata;
        }

        //Jumlah hasil kuadrat
        $sumExpDiffRatingUsersByWisata = $expDiffRatingUsersByWisata[0];
        foreach($expDiffRatingUsersByWisata as $edrubh){
            $sumExpDiffRatingUsersByWisata = array_map(function (...$arrays) {
                return array_sum($arrays);
            }, $sumExpDiffRatingUsersByWisata, $edrubh);
        }

        $sumExpDiffRatingCurrentUserByWisata = $expDiffRatingCurrentUsersByWisata[0];
        foreach($expDiffRatingCurrentUsersByWisata as $edrcubh){
            $sumExpDiffRatingCurrentUserByWisata = array_map(function (...$arrays) {
                return array_sum($arrays);
            }, $sumExpDiffRatingCurrentUserByWisata, $edrcubh);
        }

        //Akarkan jumlah hasil kuadrat
        $sqrtSumExpDiffRatingUsersByWisata = [];
        foreach($sumExpDiffRatingUsersByWisata as $key13 => $sudrubh){
            $sqrtSumExpDiffRatingUsersByWisata[$key13] = \sqrt($sudrubh);
        }

        $sqrtSumExpDiffRatingCurrentUserByWisata = \sqrt($sumExpDiffRatingCurrentUserByWisata[0]);

        //Penyebut
        $penyebut = [];
        foreach($sqrtSumExpDiffRatingUsersByWisata as $key14 => $ssedrubh){
            $penyebut[$key14] = $ssedrubh * $sqrtSumExpDiffRatingCurrentUserByWisata;
        }

        //Hasil
        $result = array_map(function ($array1, $array2) { return $array1/$array2; } , $pembilang, $penyebut);

        //Cari max result
        $maxValue = max($result);
        $maxIndex = array_keys($result, max($result));

        $result_diff = [];
        $b= 0;
        for($a=0; $a<count($result); $a++) {
            if($a != $maxIndex[0]){
                $result_diff[$b] = $result[$a];
                $b++;
            }
        }

        $wisataInCity = [];
        $usernameSimilar = "";

        if(count($result_diff) > 0){
            $maxIndexResult = array_keys($result_diff, max($result_diff));

            //Cari user similar
            $usernameSimilar = $usernamesByWisata[0][$maxIndexResult[0]];

            //Rating dari user similar
            $ratingSimilar = Rating::where('username', $usernameSimilar)->get();

            //Sorting berdasarkan rating tertinggi
            $ratingSimilar = collect($ratingSimilar);
            $ratingSimilar = $ratingSimilar->sortByDesc('angka_rating');
            $ratingSimilar = $ratingSimilar->values()->toArray();

            $idWisataSimilar = [];
            foreach($ratingSimilar as $key15 => $rs){
                $idWisataSimilar[$key15] = $rs['id_wisata'];
            }

            $idWisataUser = [];
            foreach($wisataRatingUser as $key16 => $hru){
                $idWisataUser[$key16] = $hru->id_wisata;
            }

            $idSuggestWisata = array_values(array_diff($idWisataSimilar, $idWisataUser));

            $wisataSimilar = [];
            foreach($idSuggestWisata as $key17 => $ish) {
                $wisataSimilar[$key17] = Wisata::where('id_wisata', $ish)->first();
            }

            $wisataInCity = [];
            $c = 0;
            foreach($wisataSimilar as $wisata) {
                if($wisata->kota == $city) {
                    $wisataInCity[$c] = $wisata;
                    $c++;
                }
            }

            if(count($wisataInCity) == 0) {
                $wisataRatOwn = Rating::where('username', $username)->get();
                $wisataRatOwn = collect($wisataRatOwn);
                $wisataRatOwn = $wisataRatOwn->sortByDesc('angka_rating');
                $wisataRatOwn = $wisataRatOwn->values()->toArray();

                $wisataList = [];

                foreach($wisataRatOwn as $key => $htrt) {
                    $wisataList[$key] = Wisata::where('id_wisata', $htrt['id_wisata'])->first();
                }

                $wisataInCity = $wisataList;
            }
        }else {
            $wisataRatOwn = Rating::where('username', $username)->get();
            $wisataRatOwn = collect($wisataRatOwn);
            $wisataRatOwn = $wisataRatOwn->sortByDesc('angka_rating');
            $wisataRatOwn = $wisataRatOwn->values()->toArray();

            $wisataList = [];

            foreach($wisataRatOwn as $key => $htrt) {
                $wisataList[$key] = Wisata::where('id_wisata', $htrt['id_wisata'])->first();
            }

            $wisataInCity = $wisataList;
        }

        return response()->json([
            "wisata_rec" => $wisataInCity,
            "username_similar" => $usernameSimilar,
            "city" => $city
        ], 200);
    }

    public function ownRatedWisata(Request $request) {
        $username = $request->username;

        $ratedWisata = Rating::where('username', $username)->get();
        $ratedWisata = collect($ratedWisata);
        $ratedWisata = $ratedWisata->sortByDesc('angka_rating');
        $ratedWisata = $ratedWisata->values()->toArray();

        $wisatas = [];
        $a = 0;
        foreach($ratedWisata as $rat){
            $wisatas[$a] = Wisata::where('id_wisata', $rat['id_wisata'])->first();
            $a++;
        }

        return response()->json($wisatas, 200);
    }

    public function wisataInCity(Request $request){
        $city = $request->city;
        $lat = $request->lat;
        $lng = $request->lng;

        // dd($lat, $lng);

        $wisatas = Wisata::select('*', DB::raw(
            '( ( ( acos( sin(('.$lat.' * pi() / 180)) * sin((lat * pi() / 180)) + cos(( -6.763 * pi() /180 )) * cos(( lat * pi() / 180)) * cos((( '.$lng.' - lng) * pi()/180))) ) * 180/pi() ) * 60 * 1.1515 * 1.609344 ) as km'
        ))->nearby([
            $lat,//latitude
            $lng//longitude
        ], 1000)->orderBy('km', 'asc')->get();;

        // $wisatas = Wisata::where('kota', $city)->get();
        return response()->json($wisatas, 200);
    }

    public function searchWisata(Request $request){
        $search = $request->search;
        $wisatas = [];
        $wisatasInCity = Wisata::where('kota', 'like', '%' . $search . '%')->get();
        $wisatasByName = Wisata::where('nama_wisata', 'like', '%' . $search . '%')->get();

        if($wisatasInCity->count() > 0){
            $wisatas = $wisatasInCity;
        }

        if($wisatasByName->count() > 0){
            $wisatas = $wisatasByName;
        }

        return response()->json($wisatas, 200);
    }

    public function searchTagWisata(Request $request){
        $jenis_wisata = $request->jenis_wisata;
        $wisatas = [];
        $wisatasInCity = Wisata::where('jenis_wisata', $jenis_wisata)->get();

        if($wisatasInCity->count() > 0){
            $wisatas = $wisatasInCity;
        }

        return response()->json($wisatas, 200);
    }

    function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('miles','feet','yards','kilometers','meters'); 
    }
}
