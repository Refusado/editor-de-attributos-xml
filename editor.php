<?php 
session_start();

$_SESSION['lastException'] = $typeException = $_POST['type-exception'] ?? $_SESSION['lastException'] ?? "";
$_SESSION['lastProp'] = $propId = $_POST['prop-name'] ?? $_SESSION['lastProp'] ?? 4;
$_SESSION['lastValue'] = $propValue = $_POST['prop-value'] ?? $_SESSION['lastValue'] ?? 45;
$_SESSION['lastXml'] = $string = $_POST['xml-code'] ?? $_SESSION['lastXml'] ?? "";

?>

<html>
  <head>
    <style>
      * {
        font-family: sans-serif;
        margin: 0;
      }
      html {
        margin: 8px;
      }
      .code-container {
        border: 1px solid #00000066;
        max-width: 100vw;
        margin: 14px;
      }
      form {
        border: 1px solid #00000066;
        padding: 16px;
        width: fit-content;
        margin: 0 auto 12px auto;
        text-align: end;
      }
      form input, form select {
        width: 200px;
        margin: 0 0 4px 0;
      }
    </style>
  </head>
  <form action="" method="POST">
    Código

    <?php
    echo "<input type='text' name='xml-code' id='' value='{$_SESSION['lastXml']}'>";
    ?>
    <br>
    Propriedade
    <select name="prop-name" id="" required>

      <?php
      $groundProps = array(
        "Se é dinâmico","Massa","Fricção","Restituição","Ângulo","Se tem ângulo fixo","Amortecimento linear","Amortecimento angular"
      );
      for ($i = 0; $i < count($groundProps); $i++) {
        if ($i == $_SESSION['lastProp']) {
          echo "<option selected value='$i'>$groundProps[$i] </option>";
        } else {
          echo "<option value='$i'>$groundProps[$i] </option>";
        }
      }
      ?>

    </select><br>
    Valor

    <?php
    echo "<input type='number' name='prop-value' id='' step='any' value='{$_SESSION['lastValue']}'>";
    ?>
    
    <br>
    Ignorar
    <select name="type-exception" id="">
    <option selected value="">Nenhum</option>

      <?php
      $groundTypes = array(
        "Madeira","Gelo","Trampolim","Lava","Chocolate","Terra","Grama","Areia","Nuvem","Água","Pedra","Grama com Neve","Retângulo","Circulo","Invisível","Teia de Aranha","Madeira2","Grama Laranja","Grama Rosa","Ácido"
      );
      for ($i = 0; $i < count($groundTypes); $i++) {
        if ($i == $_SESSION['lastException']) {
          echo "<option selected value='$i'> $i - $groundTypes[$i] </option>";
        } else {
          echo "<option value='$i'> $i - $groundTypes[$i] </option>";
        }
      }
      ?>

    </select><br>
    <input type="submit" value="Enviar">
  </form>
</html>

<?php
function displayXml($x){
  $withoutVersionTag = preg_replace('/<\?xml version="1.0"\?>/', '', $x);
  $tagsPattern = array('/(<)/', '/(>)/');
  $replacement = array('&lt', '&gt');
  echo preg_replace($tagsPattern, $replacement, $withoutVersionTag);
}
function exist($var){
  if (is_null($var) || $var == "" || !isset($var)) {
    return false;
  } else {
    return true;
  }
}

if (!exist($string)){
  echo "Insira o XML no campo <b>Código</b> <br>";
}
$xml = @simplexml_load_string($string);

if (exist($string) && $xml == false){
  echo "Código inválido";
} else if ($xml){
  $sentCode = $xml->saveXML();
  
  for ($i = 0; $i < count($xml->Z->S->S); $i++) {
    $allProperties = $xml->Z->S->S[$i]->attributes()->P;
    preg_match_all('/[\d\s.]+/', $allProperties, $matches);
    
    if ($matches[0] && $xml->Z->S->S[$i]->attributes()->T != $typeException) {
      $prop = array();
      for ($ii = 0; $ii < 8; $ii++) { 
        array_push($prop, $matches[0][$ii]);
      }
      $prop[$propId] = $propValue;
      
      $newProps = "$prop[0],$prop[1],$prop[2],$prop[3],$prop[4],$prop[5],$prop[6],$prop[7]" ;
      $xml->Z->S->S[$i]->attributes()->P = $newProps;
    }
  }

  $editedCode = $xml->saveXML();
  
  echo "
  <h4>Resultado:</h4>
  <div class='code-container'><code>";
  displayXml($editedCode);
  echo "</code></div>";
  
  echo "
  <h4>XML Enviado:</h4>
  <div class='code-container'><code>";
  displayXml($sentCode);
  echo "</code></div>";
}

// XML PARA TESTE:
// <C><P MEDATA=";;;;-0;0:::1-"/><Z><S><S T="0" X="134" Y="392" L="230" H="109" P="0,0,0.3,0.2,0,0,0,0"/><S T="0" X="394" Y="378" L="125" H="93" P="0,0,0.3,0.2,0,0,0,0"/><S T="0" X="543" Y="376" L="90" H="78" P="0,0,0.3,0.2,0,0,0,0"/><S T="1" X="396" Y="25" L="776" H="28" P="0,0,0,0.2,0,0,0,0"/><S T="1" X="148" Y="-16" L="283" H="36" P="0,0,0,0.2,0,0,0,0"/><S T="1" X="562" Y="-20" L="422" H="39" P="0,0,0,0.2,0,0,0,0"/><S T="7" X="292" Y="385" L="76" H="102" P="0,0,0.1,0.2,0,0,0,0"/><S T="7" X="475" Y="376" L="52" H="86" P="0,0,0.1,0.2,0,0,0,0"/><S T="7" X="619" Y="381" L="55" H="96" P="0,0,0.1,0.2,0,0,0,0"/><S T="6" X="726" Y="373" L="154" H="62" P="0,0,0.3,0.2,0,0,0,0"/><S T="6" X="765" Y="187" L="66" H="305" P="0,0,0.3,0.2,0,0,0,0"/><S T="6" X="321" Y="-21" L="86" H="48" P="0,0,0.3,0.2,0,0,0,0"/><S T="3" X="123" Y="303" L="242" H="52" P="0,0,0,20,0,0,0,0"/><S T="3" X="387" Y="298" L="109" H="54" P="0,0,0,20,0,0,0,0"/><S T="3" X="287" Y="183" L="97" H="283" P="0,0,0,20,0,0,0,0"/><S T="8" X="113" Y="75" L="232" H="55" P="0,0,0.3,0.2,0,0,0,0" c="2"/><S T="8" X="391" Y="74" L="112" H="60" P="0,0,0.3,0.2,0,0,0,0" c="2"/><S T="8" X="583" Y="299" L="281" H="61" P="0,0,0.3,0.2,0,0,0,0" c="2"/><S T="2" X="32" Y="187" L="61" H="167" P="0,0,0,1.2,0,0,0,0"/><S T="2" X="191" Y="186" L="75" H="171" P="0,0,0,1.2,0,0,0,0"/><S T="2" X="367" Y="184" L="61" H="159" P="0,0,0,1.2,0,0,0,0"/><S T="10" X="107" Y="180" L="90" H="167" P="0,0,0.3,0,0,0,0,0"/><S T="10" X="421" Y="179" L="51" H="172" P="0,0,0.3,0,0,0,0,0"/><S T="10" X="586" Y="69" L="283" H="64" P="0,0,0.3,0,0,0,0,0"/><S T="19" X="483" Y="229" L="77" H="65" P="0,0,0.3,0,0,0,0,0"/><S T="19" X="674" Y="129" L="93" H="54" P="0,0,0.3,0,0,0,0,0"/><S T="19" X="282" Y="299" L="98" H="50" P="0,0,0.3,0,0,0,0,0"/><S T="12" X="535" Y="146" L="167" H="90" P="0,0,0.3,0.2,0,0,0,0" o="324650"/><S T="12" X="623" Y="235" L="197" H="71" P="0,0,0.3,0.2,0,0,0,0" o="324650"/><S T="12" X="675" Y="169" L="98" H="46" P="0,0,0.3,0.2,0,0,0,0" o="324650"/></S><D/><O/><L/></Z></C>
