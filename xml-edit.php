<?php
$xmlTest ='
<C><P MEDATA=";;;;-0;0:::1-"/><Z><S><S T="12" X="88" Y="104" L="106" H="86" P="0,0,0.3,4321,0,0,0,0" o="324650"/><S T="12" X="81" Y="214" L="106" H="86" P="0,0,0.3,987,0,0,0,0" o="324650"/><S T="12" X="95" Y="328" L="106" H="86" P="0,0,0.3,568,0,0,0,0" o="324650"/><S T="12" X="243" Y="107" L="106" H="86" P="0,0,0.3,967,0,0,0,0" o="324650"/><S T="12" X="248" Y="212" L="106" H="86" P="0,0,0.3,1569,0,0,0,0" o="324650"/><S T="12" X="258" Y="323" L="106" H="86" P="0,0,0.3,9321,0,0,0,0" o="324650"/></S><D/><O/><L/></Z></C>
';
$xmlTest2 = '
<C><P MEDATA=";;;;-0;0:::1-"/><Z><S><S T="0" X="97" Y="372" L="186" H="55" P="0,0,0.3,0.2,0,0,0,0"/><S T="2" X="330" Y="385" L="273" H="29" P="0,0,0,1.2,0,0,0,0"/><S T="16" X="495" Y="318" L="59" H="159" P="0,0,0.3,0.2,0,0,0,0"/><S T="7" X="167" Y="126" L="333" H="38" P="0,0,0.1,0.2,0,0,0,0"/><S T="1" X="50" Y="182" L="88" H="69" P="0,0,0,0.2,0,0,0,0"/><S T="8" X="636" Y="385" L="209" H="168" P="0,0,0.3,0.2,0,0,0,0" c="2"/><S T="10" X="769" Y="210" L="53" H="377" P="0,0,0.3,0,0,0,0,0"/><S T="3" X="690" Y="288" L="91" H="25" P="0,0,0,20,0,0,0,0"/><S T="15" X="147" Y="305" L="87" H="79" P="0,0,0,0,0,0,0,0"/><S T="9" X="367" Y="60" L="255" H="90" P="0,0,0,0,0,0,0,0"/></S><D><DS X="44" Y="328"/></D><O><O X="333" Y="340" C="2" P="0"/><O X="582" Y="336" C="6" P="0"/></O><L/></Z></C>
';
$xmlTest3 = '
<C><P F="0" MEDATA=";;;;-0;0:::1-"/><Z><S><S T="12" X="398" Y="482" L="400" H="240" P="0,0,0.3,0.2,0,0,0,0" o="A1F3B0"/><S T="6" X="400" Y="380" L="400" H="40" P="0,0,0.3,0.2,0,0,0,0"/><S T="0" X="165" Y="328" L="68" H="135" P="0,0,0.3,0.2,0,0,0,0"/><S T="2" X="649" Y="380" L="99" H="40" P="0,0,0,1.2,0,0,0,0"/><S T="3" X="68" Y="322" L="125" H="139" P="0,0,0,20,0,0,0,0"/></S><D><F X="97" Y="48"/><T X="742" Y="176"/><DS X="376" Y="314"/></D><O><O X="598" Y="354" C="4" P="0"/><O X="109" Y="256" C="403" P="0"/></O><L/></Z></C>
';

$xml = $xmlTest3;
$propToEdit = 4;
$newValue = 7;

$getAllTags = '/<\/?[A-Z]{1,} ?.*?\/?>/'; // busca todas as tags do XML
$getGroundTags = '/<S .*?\/>/'; // busca as tags do XML que representam pisos (<S/>)
$getGroundProps = '/P="([0-9.]{1,}),([0-9.]{1,}),([0-9.]{1,}),([0-9.]{1,}),([0-9.]{1,}),([0-9.]{1,}),([0-9.]{1,}),([0-9.]{1,})"/'; // busca as propriedades do piso (P="")
$getGroundIdType = '/<S .*?T="(.*?)".*?\/>/'; // busca o id do tipo do piso (T="")
$getGroundRest = '/P="[0-9.]{1,},[0-9.]{1,},[0-9.]{1,},([0-9.]{1,}),[0-9.]{1,},[0-9.]{1,},[0-9.]{1,},[0-9.]{1,}"/'; // busca a restituição dos pisos

preg_match_all($getAllTags, $xml, $matchAllTags);
preg_match_all($getGroundTags, $xml, $matchSTags);
preg_match_all($getGroundProps, $xml, $matchProps);
preg_match_all($getGroundIdType, $xml, $matchIdType);
preg_match_all($getGroundRest, $xml, $matchGroundRest);

$groundsNo = count($matchSTags[0]);

for ($i = 0; $i < $groundsNo; $i++) {
  $isdyn    = $matchProps[1][$i];
  $mass     = $matchProps[2][$i];
  $friction = $matchProps[3][$i];
  $restit   = $matchProps[4][$i];
  $angle    = $matchProps[5][$i];
  $fixrotat = $matchProps[6][$i];
  $lindamp  = $matchProps[7][$i];
  $angdamp  = $matchProps[8][$i];

  $pattern = "/P=\"$isdyn,$mass,$friction,$restit,$angle,$fixrotat,$lindamp,$angdamp\"/";

  switch ($propToEdit) {
    case 1:
      $replacement = "P=\"$newValue,$mass,$friction,$restit,$angle,$fixrotat,$lindamp,$angdamp\"";
      $propName = "se é dinâmico";
      break;
    case 2:
      $replacement = "P=\"$isdyn,$newValue,$friction,$restit,$angle,$fixrotat,$lindamp,$angdamp\"";
      $propName = "sua massa";
      break;
    case 3:
      $replacement = "P=\"$isdyn,$mass,$newValue,$restit,$angle,$fixrotat,$lindamp,$angdamp\"";
      $propName = "sua fricção";
      break;
    case 4:
      $replacement = "P=\"$isdyn,$mass,$friction,$newValue,$angle,$fixrotat,$lindamp,$angdamp\"";
      $propName = "sua restituição";
      break;
    case 5:
      $replacement = "P=\"$isdyn,$mass,$friction,$restit,$newValue,$fixrotat,$lindamp,$angdamp\"";
      $propName = "seu ângulo";
      break;
    case 6:
      $replacement = "P=\"$isdyn,$mass,$friction,$restit,$angle,$newValue,$lindamp,$angdamp\"";
      $propName = "se tem o ângulo fixo";
      break;
    case 7:
      $replacement = "P=\"$isdyn,$mass,$friction,$restit,$angle,$fixrotat,$newValue,$angdamp\"";
      $propName = "seu amortecimento linear";
      break;
    case 8:
      $replacement = "P=\"$isdyn,$mass,$friction,$restit,$angle,$fixrotat,$lindamp,$newValue\"";
      $propName = "seu amortecimento angular";
      break;
  }

  $xml = preg_replace($pattern, $replacement, $xml);

  if ($i + 1 == $groundsNo) {
    echo "A propriedade dos pisos que determina $propName foi alterada para $newValue<br>";
    echo "Quantidade de pisos editados: $groundsNo<hr>";

    echo "XML enviado:";
    displayXml($xml);

    echo "Novo XML: ";
    displayXml($xml);
  }
}

echo "<style>*{font-family:sans-serif;}</style>";
function displayXml($x){ // substitui os caracteres "<" e ">" para nao serem lidos como tags no navegador e possam ser imprimidos
  $getting = array('/(<)/', '/(>)/');
  $setting = array('&lt', '&gt');
  if (gettype($x) == "array"){
    for ($i = 0; $i < count($x); $i++) {
      $disp[$i] = preg_replace($getting, $setting, $x[$i]);

      if(gettype(@$disp[0][$i]) == "array") {
        for ($w = 0; $w < count($x[0]); $w++) {
        $disp[$w][$w] = preg_replace($getting, $setting, $x[$w][$w]);
        }
      }
    };
    echo "<pre>";
    print_r($disp);
    echo "</pre>";
  } else {
    $y = preg_replace($getting, $setting, $x);
    echo "<div style='border: 1px solid black; background-color: #eeeeee; width: fit-content; max-width: 80%; margin: 8px;'><code>$y</code></div>";
  }
};
