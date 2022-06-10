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
        margin: 0 auto;
        text-align: end;
      }
      form input, form select {
        width: 200px;
        margin: 0 0 4px 0;
      }
    </style>
  </head>
  <form action="editor.php" method="POST">
    Código
    <input required type="text" name="xml-code" id=""><br>
    Propriedade
    <select name="prop-name" id="" required>
      <option value="0">Se é dinâmico</option>
      <option value="1">Massa</option>
      <option value="2">Fricção</option>
      <option value="3" selected>Restituição</option>
      <option value="4">Ângulo</option>
      <option value="5">Se tem ângulo fixo</option>
      <option value="6">Amortecimento linear</option>
      <option value="7">Amortecimento angular</option>
    </select><br>
    Valor
    <input type="number" name="prop-value" id="" value="1.2"><br>
    Ignorar
    <select name="type-exception" id="">
      <option value=" ">Nenhum</option>
      <option value="0"> 0 - Madeira </option>
      <option value="1"> 1 - Gelo </option>
      <option value="2"> 2 - Trampolim </option>
      <option value="3"> 3 - Lava </option>
      <option value="4"> 4 - Chocolate </option>
      <option value="5"> 5 - Terra </option>
      <option value="6"> 6 - Grama </option>
      <option value="7"> 7 - Areia </option>
      <option value="8" selected> 8 - Nuvem </option>
      <option value="9"> 9 - Água </option>
      <option value="10"> 10 - Pedra </option>
      <option value="11"> 11 - Grama com Neve </option>
      <option value="12"> 12 - Retângulo </option>
      <option value="13"> 13 - Circulo </option>
      <option value="14"> 14 - Invisível </option>
      <option value="15"> 15 - Teia de Aranha </option>
      <option value="16"> 16 - Madeira2 </option>
      <option value="17"> 17 - Grama Laranja </option>
      <option value="18"> 18 - Grama Rosa </option>
      <option value="19"> 19 - Ácido </option>
    </select><br>
    <input type="submit" value="Enviar">
  </form>
</html>
<?php
function displayXml($x){
  $edited = preg_replace('/<\?xml version="1.0"\?>/', '', $x);
  $pattern = array('/(<)/', '/(>)/');
  $replacement = array('&lt', '&gt');
  echo preg_replace($pattern, $toMatch, $edited);
};

if (!is_null(@$_POST['xml-code']) && @$_POST['xml-code'] != "") {
  $string = @$_POST['xml-code'];

  $xml = simplexml_load_string($string);
  $sentCode = $xml->saveXML();
  
  $propId = $_POST['prop-name'] ?? 3;
  $typeException = $_POST['type-exception'] ?? "";
  
  for ($i = 0; $i < count($xml->Z->S->S); $i++) {
    $allProperties = $xml->Z->S->S[$i]->attributes()->P;
    preg_match_all('/[\d\s.]+/', $allProperties, $matches);
    
    if ($matches[0] && $xml->Z->S->S[$i]->attributes()->T != $typeException) {
      $prop = array();
      for ($ii = 0; $ii < 8; $ii++) { 
        array_push($prop, $matches[0][$ii]);
      }
  
      if (!is_null(@$_POST['prop-value']) && @$_POST['prop-value'] != "") {
        $prop[$propId] = $_POST['prop-value'];
      }
      
      $newProps = "$prop[0],$prop[1],$prop[2],$prop[3],$prop[4],$prop[5],$prop[6],$prop[7]" ;
      $xml->Z->S->S[$i]->attributes()->P = $newProps;
    }
  }
  
  echo "<h4>Resultado:</h4>
  <div class='code-container'><code>";
  echo displayXml($xml->saveXML());
  echo "</code></div>";
  
  echo "<h4>XML Enviado:</h4>
  <div class='code-container'><code>";
  echo displayXml($sentCode);
  echo "</code></div>";
}

// XML PARA TESTE:
// <C><P MEDATA=";;;;-0;0:::1-"/><Z><S><S T="0" X="134" Y="392" L="230" H="109" P="0,0,0.3,0.2,0,0,0,0"/><S T="0" X="394" Y="378" L="125" H="93" P="0,0,0.3,0.2,0,0,0,0"/><S T="0" X="543" Y="376" L="90" H="78" P="0,0,0.3,0.2,0,0,0,0"/><S T="1" X="396" Y="25" L="776" H="28" P="0,0,0,0.2,0,0,0,0"/><S T="1" X="148" Y="-16" L="283" H="36" P="0,0,0,0.2,0,0,0,0"/><S T="1" X="562" Y="-20" L="422" H="39" P="0,0,0,0.2,0,0,0,0"/><S T="7" X="292" Y="385" L="76" H="102" P="0,0,0.1,0.2,0,0,0,0"/><S T="7" X="475" Y="376" L="52" H="86" P="0,0,0.1,0.2,0,0,0,0"/><S T="7" X="619" Y="381" L="55" H="96" P="0,0,0.1,0.2,0,0,0,0"/><S T="6" X="726" Y="373" L="154" H="62" P="0,0,0.3,0.2,0,0,0,0"/><S T="6" X="765" Y="187" L="66" H="305" P="0,0,0.3,0.2,0,0,0,0"/><S T="6" X="321" Y="-21" L="86" H="48" P="0,0,0.3,0.2,0,0,0,0"/><S T="3" X="123" Y="303" L="242" H="52" P="0,0,0,20,0,0,0,0"/><S T="3" X="387" Y="298" L="109" H="54" P="0,0,0,20,0,0,0,0"/><S T="3" X="287" Y="183" L="97" H="283" P="0,0,0,20,0,0,0,0"/><S T="8" X="113" Y="75" L="232" H="55" P="0,0,0.3,0.2,0,0,0,0" c="2"/><S T="8" X="391" Y="74" L="112" H="60" P="0,0,0.3,0.2,0,0,0,0" c="2"/><S T="8" X="583" Y="299" L="281" H="61" P="0,0,0.3,0.2,0,0,0,0" c="2"/><S T="2" X="32" Y="187" L="61" H="167" P="0,0,0,1.2,0,0,0,0"/><S T="2" X="191" Y="186" L="75" H="171" P="0,0,0,1.2,0,0,0,0"/><S T="2" X="367" Y="184" L="61" H="159" P="0,0,0,1.2,0,0,0,0"/><S T="10" X="107" Y="180" L="90" H="167" P="0,0,0.3,0,0,0,0,0"/><S T="10" X="421" Y="179" L="51" H="172" P="0,0,0.3,0,0,0,0,0"/><S T="10" X="586" Y="69" L="283" H="64" P="0,0,0.3,0,0,0,0,0"/><S T="19" X="483" Y="229" L="77" H="65" P="0,0,0.3,0,0,0,0,0"/><S T="19" X="674" Y="129" L="93" H="54" P="0,0,0.3,0,0,0,0,0"/><S T="19" X="282" Y="299" L="98" H="50" P="0,0,0.3,0,0,0,0,0"/><S T="12" X="535" Y="146" L="167" H="90" P="0,0,0.3,0.2,0,0,0,0" o="324650"/><S T="12" X="623" Y="235" L="197" H="71" P="0,0,0.3,0.2,0,0,0,0" o="324650"/><S T="12" X="675" Y="169" L="98" H="46" P="0,0,0.3,0.2,0,0,0,0" o="324650"/></S><D/><O/><L/></Z></C>
