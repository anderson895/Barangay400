<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barangay ID (Front & Back)</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col items-center min-h-screen bg-gray-200 p-6 gap-6">

  <!-- FRONT SIDE -->
<div class="w-[700px] border-2 border-black bg-white p-6 min-h-400px]">
  
  <!-- Header -->
  <div class="text-center">
    <h1 class="font-bold text-sm uppercase">Republic of the Philippines</h1>
    <p class="text-xs">City of Manila</p>
    <p class="text-xs">District IV</p>
    <p class="text-xs font-bold uppercase">Barangay 400 Zone 41</p>
  </div>

  <!-- Logos -->
  <div class="flex justify-between items-center mt-2">
    <img src="https://upload.wikimedia.org/wikipedia/commons/4/42/Manila_Seal.svg" alt="Seal" class="w-16 h-16">
    <img src="https://upload.wikimedia.org/wikipedia/commons/1/12/Barangay_Logo.png" alt="Barangay Logo" class="w-16 h-16">
  </div>

  <!-- Title -->
  <h2 class="text-center font-bold underline uppercase text-sm mt-2">
    Barangay Identification Card
  </h2>

  <!-- Photo + Details -->
  <div class="flex mt-4 gap-4">
    <!-- Photo Box -->
   <div class="w-24 h-28 border border-black flex items-center justify-center overflow-hidden bg-gray-100">
  <img src="<?=$image?>" alt="ID Photo" class="w-full h-full object-contain" />
</div>


    <!-- Details -->
    <div class="flex-1 text-xs leading-5">
      <p class="text-xs">
        This is to certify that 
        <span class="border-b border-black inline-block w-64 text-center">
          <?=$row['first_name']?> <?=$row['middle_name']?> <?=$row['last_name']?>
        </span>
      </p>

      <p class="mt-1 text-xs">
        of 
        <span class="border-b border-black inline-block w-64 text-center break-words">
          <?=$row['user_address']?>
        </span>
      </p>

      <p class="mt-2">
        Whose picture and signature appears hereon is a 
        <span class="font-bold uppercase">Registered Member</span> of this barangay.
      </p>
      <p class="mt-1">
        This identification card is being issued for whatever purpose it may serve.
      </p>

      <!-- ID & Date -->
      <div class="flex justify-between mt-4 text-[10px]">
        <div>
          <p>ID No.: <span class="border-b border-black inline-block w-28"><?=$current_year?>-<?=$row['BID_id']?>-<?=$row['res_id']?></span></p>
          <p class="mt-1">Date of issuance: <?=$today?></p>
          <p class="mt-1 font-bold">VALID 1 YR UPON ISSUANCE</p>
        </div>

        <!-- Signature -->
        <div class="flex flex-col items-center mt-5"> 
          <div class="border-b border-black w-32"></div>
          <p class="mt-1">Signature</p>
        </div>

      </div>
    </div>
  </div>
</div>


  <!-- BACK SIDE -->
<div class="w-[700px] border-2 border-black bg-white p-6 min-h-[400px]">
  <!-- Top content -->
  <div>
    <div class="grid grid-cols-2 gap-4 text-xs">
      <div>
        <p>Precinct No.: <span class="border-b border-black inline-block w-32"><?=$row['precinctNumber']?></span></p>
        <p class="mt-2">Date of Birth: <span class="border-b border-black inline-block w-32"><?=$row['birthday']?></span></p>
        <p class="mt-2">Height: <span class="border-b border-black inline-block w-16"><?=$row['height']?></span>
           Weight: <span class="border-b border-black inline-block w-16"><?=$row['weight']?></span></p>
        <p class="mt-2">SSS / GSIS No.: <span class="border-b border-black inline-block w-32"><?=$row['SSSGSIS_Number']?></span></p>
      </div>
      <div>
        <p>Blood Type: <span class="border-b border-black inline-block w-32"><?=$row['bloodType']?></span></p>
        <p class="mt-2">Place of Birth: <span class="border-b border-black inline-block w-32"><?=$row['birthplace']?></span></p>
        <p class="mt-2">Status: <span class="border-b border-black inline-block w-32"><?=$row['civilStatus']?></span></p>
        <p class="mt-2">TIN No.: <span class="border-b border-black inline-block w-32"><?=$row['TIN_number']?></span></p>
      </div>
    </div>

    <div class="mt-6 text-xs">
      <p class="text-red-600 font-bold">IN CASE OF EMERGENCY, PLEASE NOTIFY:</p>
      <p class="mt-2">Name: <span class="border-b border-black inline-block w-64"><?=$row['personTwoName']?></span></p>
      <p class="mt-2">Address: <span class="border-b border-black inline-block w-72"><?=$row['personTwoAddress']?></span></p>
      <p class="mt-2">Contact No.: <span class="border-b border-black inline-block w-64"><?=$row['personTwoContactInfo']?></span></p>
    </div>
  </div>

  <!-- Officials at bottom -->
  <div class="flex justify-between items-center">
    <div class="text-center">
      <p class="font-bold underline">IMELDA M. SAQUNG</p>
      <p class="text-red-600 text-xs">Barangay Secretary</p>
    </div>

    <div class="flex flex-col items-center">
      <!-- <div class="w-20 h-20 border border-black mb-2 flex items-center justify-center text-[10px]">
        PHOTO
      </div> -->
      <div class="h-20"></div>
      <p class="font-bold underline">Hon. FELIX C. TAGUBA</p>
      <p class="text-red-600 text-xs">Punong Barangay</p>
    </div>
  </div>
</div>
</body>
</html>
