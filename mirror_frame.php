<?php
// mirror_frame.php
session_start();
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
if (!is_logged_in()) {
  header('Location: login.php');
  exit;
}

// 1) Cihazı MAC’e göre alıyoruz
$deviceMac = '08:A6:F7:10:9E:DC';
$stmt = $pdo->prepare("SELECT id FROM devices WHERE mac = :mac");
$stmt->execute(['mac'=>$deviceMac]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
  die("Cihaz bulunamadı (MAC: $deviceMac).");
}
$deviceId = (int)$row['id'];

require __DIR__ . '/includes/header.php';
?>
<!DOCTYPE html>
<style>
    #svg-container { flex:1; display:flex; justify-content:center; align-items:center; background:#fff; overflow:auto; }
    #mirror-svg { height:90%; width:auto; max-width:100%; max-height:100%; display:block; }
    /* Tüm id’li şekiller click yakalasın */
    #mirror-svg [id] { cursor:pointer; pointer-events:all; }
    /* fil0’ları da tıklanabilir kılaım */
    #mirror-svg .fil0 { fill:transparent !important; pointer-events:all; }
    #mirror-svg .str0 { stroke-width:2px!important; vector-effect:non-scaling-stroke; }

  </style>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Ayna Çerçevesi</title>
  <style>
    #mirrorSvg { width:100%; max-width:780px; }
    #controls { margin-top:1rem; }
  </style>
</head>
<body>
  <div class="container mt-4">
    <h1>Ayna Çerçevesi</h1>

    <!-- SVG’inizi buraya alın -->
    <svg id="mirror-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 91025.02 280444.28" preserveAspectRatio="xMidYMid meet">
        <defs>
          <style><![CDATA[ .str0 { stroke:#3F00FF; stroke-linejoin:bevel; stroke-miterlimit:22.9256 } .fil0 { fill:none } ]]></style>
        </defs>
         <g id="nEW">
  <polygon id="shape0" class="fil0 str0" points="3.81,130878.93 12191.94,123546.51 6561.89,117916.46 3.81,117916.46 "/>
  <polygon id="shape1" class="fil0 str0" points="6671.64,117856.43 9933.09,114594.97 19501.36,114594.97 12556.62,123327.12 12297.95,123482.73 "/>
  <polygon id="shape2" class="fil0 str0" points="3.81,117796.4 3.81,108036.89 8162.6,116195.69 6561.89,117796.4 "/>
  <polygon id="shape3" class="fil0 str0" points="10053.15,114474.92 14890.46,109637.61 19654.84,114401.99 19596.83,114474.92 "/>
  <polygon id="shape4" class="fil0 str0" points="22854.87,109612.75 23091.9,110080.31 19730.05,114307.41 15035.37,109612.75 "/>
  <polygon id="shape5" class="fil0 str0" points="1749.44,109612.75 14745.54,109612.75 8247.49,116110.8 "/>
  <polygon id="shape6" class="fil0 str0" points="14950.48,109492.69 14950.48,102934.62 17949.29,99935.81 22794.02,109492.69 "/>
  <polygon id="shape7" class="fil0 str0" points="8392.41,109492.69 14830.43,109492.69 14830.43,103054.67 "/>
  <polygon id="shape8" class="fil0 str0" points="8307.52,109407.81 8307.52,99733.19 13144.83,104570.5 "/>
  <polygon id="shape9" class="fil0 str0" points="3.81,107867.12 3.81,104595.36 6586.75,98012.42 8187.47,99613.13 8187.47,109492.69 1629.39,109492.69 "/>
  <polygon id="shape10" class="fil0 str0" points="9968.26,101224.15 13229.72,104485.61 17892.18,99823.14 16119.77,96326.82 9968.26,96326.82 "/>
  <polygon id="shape11" class="fil0 str0" points="5010.9,96266.79 9848.21,101104.1 9848.21,91429.48 "/>
  <polygon id="shape12" class="fil0 str0" points="9968.26,91309.43 12362.54,88915.15 16058.91,96206.76 9968.26,96206.76 "/>
  <polygon id="shape13" class="fil0 str0" points="4926.01,96181.9 3.81,91259.7 3.81,89683.85 11424.06,89683.85 "/>
  <polygon id="shape14" class="fil0 str0" points="4865.98,89563.8 4865.98,86362.37 3.81,86362.37 3.81,89563.8 "/>
  <polygon id="shape15" class="fil0 str0" points="4986.04,89563.8 11544.11,89563.8 12305.42,88802.49 7893.13,80098.62 4986.04,83005.72 "/>
  <polygon id="shape16" class="fil0 str0" points="3.81,86242.31 4865.98,86242.31 4865.98,83005.72 3.81,78143.55 "/>
  <polygon id="shape17" class="fil0 str0" points="88.7,78058.66 8742.48,78058.66 7764.43,79844.72 7836.02,79985.96 4926.01,82895.97 "/>
  <polygon id="shape18" class="fil0 str0" points="3.81,77938.61 3.81,74702.01 4865.98,69839.84 4865.98,77938.61 "/>
  <polygon id="shape19" class="fil0 str0" points="3.81,74532.24 3.81,68179.1 3180.38,71355.67 "/>
  <polygon id="shape20" class="fil0 str0" points="3265.27,71270.78 8102.58,66433.47 3.81,66433.47 3.81,68009.32 "/>
  <polygon id="shape21" class="fil0 str0" points="3.81,66313.42 3.81,64737.56 6526.72,58214.65 6526.72,66313.42 "/>
  <polygon id="shape22" class="fil0 str0" points="3.81,64567.79 8162.6,56408.99 3.81,48250.2 "/>
  <polygon id="shape23" class="fil0 str0" points="8392.41,56348.97 11919.95,56348.97 11036.93,53838.04 11212.81,53528.57 "/>
  <polygon id="shape24" class="fil0 str0" points="8247.49,56324.11 9848.21,54723.39 9848.21,44928.72 3350.16,51426.77 "/>
  <polygon id="shape25" class="fil0 str0" points="9968.26,44843.83 16148.82,44843.83 11436.37,53135.23 9968.26,54603.34 "/>
  <polygon id="shape26" class="fil0 str0" points="3.81,48080.43 3265.27,51341.88 11484.09,43123.06 6561.89,38200.87 3.81,38200.87 "/>
  <polygon id="shape27" class="fil0 str0" points="10053.15,44723.78 15403.74,39373.19 17102.59,43165.71 16217.05,44723.78 "/>
  <polygon id="shape28" class="fil0 str0" points="11629,42978.15 11629,30946.4 15351.21,39255.93 "/>
  <polygon id="shape29" class="fil0 str0" points="11508.95,42978.15 5010.9,36480.1 11300.08,30190.92 11294.78,30200.28 11508.95,30678.39 "/>
  <polygon id="shape30" class="fil0 str0" points="3.81,38080.81 6441.84,38080.81 3.81,31642.79 "/>
  <polygon id="shape31" class="fil0 str0" points="88.7,31557.9 9763.32,31557.9 4926.01,36395.21 "/>
  <polygon id="shape32" class="fil0 str0" points="4865.98,31437.85 4865.98,23219.03 1579.67,19932.71 3.81,19932.71 3.81,31437.85 "/>
  <polygon id="shape33" class="fil0 str0" points="4986.04,23254.19 8222.63,23254.19 12687.31,27718.88 11520.66,29800.54 9883.37,31437.85 4986.04,31437.85 "/>
  <polygon id="shape34" class="fil0 str0" points="4950.87,23134.14 11424.06,23134.14 4926.01,16636.09 1689.41,19872.68 "/>
  <polygon id="shape35" class="fil0 str0" points="3.81,19812.66 3.81,13289.74 6526.72,13289.74 6526.72,14865.6 1579.67,19812.66 "/>
  <polygon id="shape36" class="fil0 str0" points="3.81,13169.69 3.81,9968.26 6526.72,9968.26 6526.72,13169.69 "/>
  <polygon id="shape37" class="fil0 str0" points="6646.78,14745.54 10653.72,10738.6 6646.78,6731.67 "/>
  <polygon id="shape38" class="fil0 str0" points="3.81,9848.21 6526.72,9848.21 6526.72,6611.61 3.81,88.7 "/>
  <polygon id="shape39" class="fil0 str0" points="6611.61,18151.91 16491.17,18151.91 16491.17,16576.06 10738.6,10823.49 5010.9,16551.2 "/>
  <polygon id="shape40" class="fil0 str0" points="6731.67,18271.97 13169.69,18271.97 13169.69,23134.14 11593.84,23134.14 "/>
  <polygon id="shape41" class="fil0 str0" points="12748.32,27610.1 8392.41,23254.19 15192.54,23254.19 "/>
  <polygon id="shape42" class="fil0 str0" points="15259.91,23134.14 13289.74,23134.14 13289.74,18271.97 16526.34,18271.97 19707.41,21453.04 16425.24,21057.38 "/>
  <polygon id="shape43" class="fil0 str0" points="16611.23,16611.23 19812.66,16611.23 19812.23,21388.08 16611.23,18187.08 "/>
  <polygon id="shape44" class="fil0 str0" points="13374.63,13289.74 19812.66,13289.74 19812.66,16491.17 16576.06,16491.17 "/>
  <polygon id="shape45" class="fil0 str0" points="3410.18,3325.29 19847.82,3325.29 24770.02,8247.49 19847.82,13169.69 13254.58,13169.69 "/>
  <polygon id="shape46" class="fil0 str0" points="3290.13,3205.24 19727.77,3205.24 16526.34,3.81 88.7,3.81 "/>
  <polygon id="shape47" class="fil0 str0" points="24854.91,8162.6 33013.7,3.81 16696.11,3.81 "/>
  <polygon id="shape48" class="fil0 str0" points="19932.7,13374.62 29147.66,22589.59 19932.27,21479.75 "/>
  <polygon id="shape49" class="fil0 str0" points="29777.1,8222.63 31412.98,6586.75 29777.1,4950.87 29006.76,4180.53 19957.57,13229.72 26515.65,19787.79 29777.1,16526.34 "/>
  <polygon id="shape50" class="fil0 str0" points="29897.16,4901.15 31522.73,6526.72 36420.07,6526.72 36420.07,4865.98 46384.52,4865.98 46384.52,3.81 33183.48,3.81 29091.65,4095.64 "/>
  <polygon id="shape51" class="fil0 str0" points="46504.57,4781.09 51281.86,3.81 46504.57,3.81 "/>
  <polygon id="shape52" class="fil0 str0" points="36540.12,4986.04 46299.63,4986.04 38140.84,13144.83 36540.12,11544.11 "/>
  <polygon id="shape53" class="fil0 str0" points="26600.54,19872.68 31437.85,15035.37 31437.85,22865.4 29340.69,22612.84 "/>
  <polygon id="shape54" class="fil0 str0" points="31557.9,22879.86 36420.07,23465.43 36420.07,10053.15 31557.9,14915.32 "/>
  <polygon id="shape55" class="fil0 str0" points="36540.12,23479.88 37222.49,23562.06 41428.7,16602.46 36540.12,11713.89 "/>
  <polygon id="shape56" class="fil0 str0" points="41492.66,16496.65 43598.13,13012.93 50493.84,12381.68 44783.8,6671.64 38225.73,13229.72 "/>
  <polygon id="shape57" class="fil0 str0" points="51451.63,3.81 58009.71,3.81 58009.71,3205.24 48250.2,3205.24 "/>
  <polygon id="shape58" class="fil0 str0" points="54688.23,3325.29 54688.23,11997.72 50649.38,12367.44 44868.69,6586.75 48130.15,3325.29 "/>
  <polygon id="shape59" class="fil0 str0" points="58129.76,3240.41 64712.7,9823.35 74532.24,3.81 58129.76,3.81 "/>
  <polygon id="shape60" class="fil0 str0" points="74702.01,3.81 77938.61,3.81 77938.61,6526.72 68179.1,6526.72 "/>
  <polygon id="shape61" class="fil0 str0" points="78058.66,3.81 80799.88,3.81 80799.88,6526.72 78058.66,6526.72 "/>
  <polygon id="shape62" class="fil0 str0" points="66458.33,8247.49 74617.12,16406.28 74617.12,6646.78 68059.05,6646.78 "/>
  <polygon id="shape63" class="fil0 str0" points="74737.18,6646.78 80799.88,6646.78 80799.88,12004.32 74737.18,18067.03 "/>
  <polygon id="shape64" class="fil0 str0" points="74822.07,18151.91 80799.88,12174.1 80799.88,18151.91 "/>
  <polygon id="shape65" class="fil0 str0" points="64772.73,14950.48 69634.9,14950.48 69634.9,23169.3 68034.18,24770.02 64772.73,21508.56 "/>
  <polygon id="shape66" class="fil0 str0" points="64772.73,9933.09 64772.73,14830.43 72871.49,14830.43 66373.44,8332.38 "/>
  <polygon id="shape67" class="fil0 str0" points="54808.28,9848.21 64567.79,9848.21 58044.88,3325.29 54808.28,3325.29 "/>
  <polygon id="shape68" class="fil0 str0" points="54808.28,9968.26 59585.56,9968.26 54313.74,15240.07 54646.88,12122.05 54808.28,12107.28 "/>
  <polygon id="shape69" class="fil0 str0" points="59670.45,10053.15 59670.45,21388.51 54237.32,15955.37 54293.43,15430.17 "/>
  <polygon id="shape70" class="fil0 str0" points="54220.93,16108.76 53786.33,20176.44 59670.45,22413.09 59670.45,21558.29 "/>
  <polygon id="shape71" class="fil0 str0" points="59790.5,22458.72 59790.5,21593.45 64652.68,21593.45 64652.68,24190.87 64347.4,24190.87 "/>
  <polygon id="shape72" class="fil0 str0" points="69754.95,23049.25 74617.12,18187.08 74617.12,16576.06 72991.55,14950.48 69754.95,14950.48 "/>
  <polygon id="shape73" class="fil0 str0" points="68119.07,24854.91 69634.9,23339.08 69634.9,26370.73 "/>
  <polygon id="shape74" class="fil0 str0" points="64772.73,21678.34 64772.73,33013.7 66330.99,31455.42 71210.75,26575.68 69670.07,26575.68 "/>
  <polygon id="shape75" class="fil0 str0" points="64373.6,24310.92 64652.68,24310.92 64652.68,33133.75 57180.78,40605.64 57467.52,38230.75 "/>
  <polygon id="shape76" class="fil0 str0" points="74702.01,18271.97 76313.03,18271.97 80799.88,22758.82 80799.88,26455.62 69754.95,26455.62 69754.95,23219.03 "/>
  <polygon id="shape77" class="fil0 str0" points="76482.81,18271.97 80799.88,18271.97 80799.88,22589.05 "/>
  <polygon id="shape78" class="fil0 str0" points="74822.07,26575.68 80799.88,26575.68 80799.88,32553.49 "/>
  <polygon id="shape79" class="fil0 str0" points="66518.36,31437.85 79514.46,31437.85 74652.29,26575.68 71380.53,26575.68 "/>
  <polygon id="shape80" class="fil0 str0" points="74737.18,31557.9 79634.51,31557.9 80799.88,32723.27 80799.88,38080.81 74737.18,38080.81 "/>
  <polygon id="shape81" class="fil0 str0" points="74617.12,31557.9 74617.12,38115.98 68094.21,44638.89 68094.21,31557.9 "/>
  <polygon id="shape82" class="fil0 str0" points="63111.99,34844.22 66398.3,31557.9 67974.16,31557.9 67974.16,44723.78 63111.99,44723.78 "/>
  <polygon id="shape83" class="fil0 str0" points="57157.47,40798.74 62991.93,34964.27 62991.93,44758.94 55812.54,51938.34 "/>
  <polygon id="shape84" class="fil0 str0" points="74702.01,38200.87 80799.88,38200.87 80799.88,46384.52 71415.69,46384.52 71415.69,41487.18 "/>
  <polygon id="shape85" class="fil0 str0" points="68119.07,44783.8 71295.64,41607.24 71295.64,47960.37 "/>
  <polygon id="shape86" class="fil0 str0" points="71415.69,46504.57 80799.88,46504.57 80799.88,51366.74 74702.01,51366.74 71415.69,48080.43 "/>
  <polygon id="shape87" class="fil0 str0" points="63111.99,44843.83 68009.32,44843.83 72871.49,49706 63111.99,49706 "/>
  <polygon id="shape88" class="fil0 str0" points="62991.93,44928.72 62991.93,58009.71 60792.17,58009.71 55861.03,52059.62 "/>
  <polygon id="shape89" class="fil0 str0" points="63111.99,58009.71 68009.32,58009.71 74592.26,51426.77 72991.55,49826.06 63111.99,49826.06 "/>
  <polygon id="shape90" class="fil0 str0" points="60891.67,58129.76 62991.93,58129.76 62991.93,60664.02 "/>
  <polygon id="shape91" class="fil0 str0" points="63111.99,58129.76 72956.38,58129.76 72956.38,60297.06 64734.42,62766.58 63111.99,60808.89 "/>
  <polygon id="shape92" class="fil0 str0" points="68179.1,58009.71 77973.77,58009.71 80799.88,55183.6 80799.88,51486.8 74702.01,51486.8 "/>
  <polygon id="shape93" class="fil0 str0" points="73076.44,58129.76 77853.72,58129.76 73590.45,62393.03 73076.44,60455.25 "/>
  <polygon id="shape94" class="fil0 str0" points="76397.92,59755.34 80799.88,55353.37 80799.88,66808.79 79634.51,67974.16 76397.92,67974.16 "/>
  <polygon id="shape95" class="fil0 str0" points="73626.04,62527.22 76277.87,59875.39 76277.87,68009.32 75331.29,68955.89 "/>
  <polygon id="shape96" class="fil0 str0" points="73418.67,71673.03 74677.15,72931.52 79514.46,68094.21 76362.75,68094.21 75366.89,69090.08 75428.86,69323.69 "/>
  <polygon id="shape97" class="fil0 str0" points="78023.49,69754.95 80799.88,69754.95 80799.88,79054.26 74762.04,73016.41 "/>
  <polygon id="shape98" class="fil0 str0" points="73076.44,72073 73340.38,71764.53 79514.46,77938.61 73076.44,77938.61 "/>
  <polygon id="shape99" class="fil0 str0" points="70649.64,74909.24 72956.38,72213.31 72956.38,77973.77 70021.47,80908.68 "/>
  <polygon id="shape100" class="fil0 str0" points="71440.56,79659.38 77938.61,86157.43 77938.61,78058.66 73041.27,78058.66 "/>
  <polygon id="shape101" class="fil0 str0" points="69636.9,84581.57 70001.61,81098.32 71355.67,79744.26 76192.98,84581.57 "/>
  <polygon id="shape102" class="fil0 str0" points="69624.33,84701.63 74532.24,84701.63 69050.35,90183.51 "/>
  <polygon id="shape103" class="fil0 str0" points="69030.49,90373.15 74617.12,84786.51 74617.12,92885.28 72498.25,92885.28 68959.97,91046.71 "/>
  <polygon id="shape104" class="fil0 str0" points="74737.18,84701.63 76313.03,84701.63 79514.46,87903.06 74737.18,87903.06 "/>
  <polygon id="shape105" class="fil0 str0" points="78058.66,78058.66 79634.51,78058.66 80799.88,79224.03 80799.88,89018.7 78058.66,86277.48 "/>
  <polygon id="shape106" class="fil0 str0" points="74822.07,88023.11 79634.51,88023.11 80799.88,89188.48 80799.88,92885.28 79684.24,92885.28 "/>
  <polygon id="shape107" class="fil0 str0" points="74737.18,88108 79514.46,92885.28 74737.18,92885.28 "/>
  <polygon id="shape108" class="fil0 str0" points="78143.55,93005.33 80799.88,93005.33 80799.88,95661.67 "/>
  <polygon id="shape109" class="fil0 str0" points="72729.29,93005.33 77973.77,93005.33 80799.88,95831.45 80799.88,97198.99 "/>
  <polygon id="shape110" class="fil0 str0" points="78143.55,69634.9 80799.88,66978.56 80799.88,69634.9 "/>
  <polygon id="shape111" class="fil0 str0" points="3.81,91429.48 6501.86,97927.53 3.81,104425.58 "/>
  <polygon id="shape112" class="fil0 str0" points="4986.04,77938.61 8808.23,77938.61 13289.68,69754.95 4986.04,69754.95 "/>
  <polygon id="shape113" class="fil0 str0" points="5070.92,69634.9 13355.43,69634.9 15326.47,66035.55 14298.33,63111.99 11593.84,63111.99 "/>
  <polygon id="shape114" class="fil0 str0" points="11962.17,56469.02 14256.11,62991.93 11629,62991.93 11629,56469.02 "/>
  <polygon id="shape115" class="fil0 str0" points="6646.78,66313.42 8222.63,66313.42 11508.95,63027.1 11508.95,56469.02 8272.35,56469.02 6646.78,58094.6 "/>
  <polygon id="shape116" class="fil0 str0" points="29897.16,16406.28 36335.18,9968.26 29897.16,9968.26 "/>
  <polygon id="shape117" class="fil0 str0" points="59790.5,21388.51 59790.5,9968.26 64652.68,9968.26 64652.68,16526.34 "/>
  <polygon id="shape118" class="fil0 str0" points="59875.39,21473.4 64652.68,21473.4 64652.68,16696.11 "/>
  <polygon id="shape119" class="fil0 str0" points="29897.16,8272.35 31522.73,6646.78 36420.07,6646.78 36420.07,9848.21 29897.16,9848.21 "/>
 </g>
      </svg>

    <div id="controls">
      <label>Şekil:
        <select id="shapeSelect">
          <option value="">— Tümü —</option>
        </select>
      </label>
      <label>Renk:
        <input type="color" id="colorPicker" value="#ff0000">
      </label>
      <button id="applyBtn">Uygula</button>
    </div>
  </div>

  <script>
    // 2) Örnek mapping → shape ID → LED dizileri
    const part2Map = {
      shape1: [21, 74, 75, 192],
      shape2: [5, 18, 19, 47],
      // … gerçek mapping’inizi buraya koyacaksınız …
    };

    // 3) Select menüyü dolduralım
    const shapeSelect = document.getElementById('shapeSelect');
    Object.keys(part2Map).forEach(id=>{
      const o = document.createElement('option');
      o.value = id; o.textContent = id;
      shapeSelect.appendChild(o);
    });

    // 4) SVG öğelerine click eventi
    const svg = document.getElementById('mirrorSvg');
    Object.keys(part2Map).forEach(id=>{
      const el = svg.getElementById(id);
      if (!el) return;
      el.style.cursor = 'pointer';
      el.addEventListener('click', ()=> shapeSelect.value = id);
    });

    // 5) Sabit deviceId
    const deviceId = <?= $deviceId ?>;

    // 6) Uygula butonu
    document.getElementById('applyBtn').addEventListener('click', ()=>{
      const sel = shapeSelect.value;
      const leds = sel
        ? part2Map[sel]
        : Object.values(part2Map).flat();

      // 78×49 matris (toplam 3822 pixel)
      const frame = Array(78*49).fill('000000');
      const c = document.getElementById('colorPicker').value.slice(1).toUpperCase();
      leds.forEach(i=> frame[i] = c );

      const payload = {
        frames:    [ frame ],
        delay:     500,
        brightness: 80
      };

      fetch('command_api.php', {
        method: 'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
          device_id: deviceId,
          frame:     JSON.stringify(payload)
        })
      })
      .then(r=> r.json())
      .then(j=> {
        if (!j.success) alert('Hata: '+(j.msg||'Gönderilemedi'));
      })
      .catch(()=> alert('Sunucu hatası'));
    });
  </script>
</body>
</html>
