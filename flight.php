<table  border="0" cellpadding="0" cellspacing="0" style="margin-top:10px; padding-left:5px; padding-right:5px; background-color:#eeeeee;">
  <tr>
    <td rowspan="2" style="vertical-align: top; padding-left: 25px; padding-right: 5px;"><!-- = = = = = site content = = = = = -->
      <script type="text/javascript">
var data2 = Array(
  //    typ,ant,speed,speed2,verbr,lager)
  //    0  , 1, 2   , 3    , 4 , 5      )
  Array(201, 1, 5000, 10000, 10, 5000), //KT
  Array(202, 1, 7500, 0, 50, 25000),    //GT
  Array(203, 1, 12500, 0, 20, 50),      //LJ
  Array(204, 2, 10000, 0, 75, 100),     //SJ
  Array(205, 2, 15000, 0, 300, 800),    //KRZ
  Array(206, 3, 10000, 0, 500, 1500),   //SS
  Array(207, 2, 2500, 0, 1000, 7500),   //KS
  Array(208, 1, 2000, 0, 300, 20000),   //REC
  Array(209, 1, 100000000, 0, 1, 0),    //Spio
  Array(211, 2, 4000, 5000, 1000, 500), //Bomber
  Array(212, 3, 5000, 0, 1000, 2000),   //Zer
  Array(213, 3, 100, 0, 1, 1000000),    //TS
  Array(214, 3, 10000, 0, 250, 750),
	Array(215, 3, 90, 0, 450000, 10000000)     //SK
);

function div(a, b) {
  return Math.floor(a / b);
}

function mod(a, b) {
  return a % b; // YEAH ^^
}

function pc(s1) {
  return pointconvert(s1);
}

function pointconvert(s) {
  var sx = '';
  s = s + '';
  for (ipc = s.length; ipc >= 0; ipc--) {
    sx = s.charAt(ipc) + sx;
    if ((div(s.length - ipc,3) == (s.length - ipc) / 3) && (s.length != ipc) && (0 != ipc)) sx = '.' + sx;
  }
  return sx;
}

function retint(s) {
  var sxx = '';
  s = s + '';
  var sx = s.toUpperCase();
  for(ir = 0; ir < sx.length; ir++) {
    if(sx.charCodeAt(ir) >= 48 && sx.charCodeAt(ir) <= 57) {
      sxx = sxx + sx.charAt(ir);
    }
  }
  return sxx;
}
function chkint(id) {
  if(id.value != retint(id.value)) {
    id.value = retint(id.value);
  }
}
function chkval(id) {
  if (id.value == '') id.value = '0';
}
function chkval1(id) {
  if (id.value == '') id.value = '1';
}

function berechne_table(id) {
  id++;
  id--;
  var antrieb = Array(document.getElementById('vbt').value,  document.getElementById('imp').value,  document.getElementById('ha').value);
  var spd = 0;

//-- data2 array --//

  for (i = 0; i < data2.length; i++) {
    if ((data2[i][0] == id) || (id == -1)) {
      spd = data2[i][2] * (1 + antrieb[data2[i][1]-1] * data2[i][1] / 10); //normal
      if (data2[i][3] != 0) {                                                                        //-exceptions-
        if ((data2[i][0] == 201) && (antrieb[1] > 4)) spd = data2[i][3] * (1 + antrieb[1] * 2 / 10); //kt neu
        if ((data2[i][0] == 211) && (antrieb[2] > 7)) spd = data2[i][3] * (1 + antrieb[2] * 3 / 10); //bomber neu
      }
      document.getElementById('s' + data2[i][0]).firstChild.nodeValue = pc(Math.round(spd));
      document.getElementById('l' + data2[i][0]).firstChild.nodeValue = pc(data2[i][5] * document.getElementById('i' + data2[i][0]).value);
    }
  }
  berechne();
}

function berechne() {
  var start  = Array(document.getElementById('st_gal').value, document.getElementById('st_sys').value, document.getElementById('st_pla').value);
  var ziel   = Array(document.getElementById('zi_gal').value, document.getElementById('zi_sys').value, document.getElementById('zi_pla').value);
  var anz    = 0;
  var enf    = 0;
  var lag    = 0;
  var spd    = 110000000000;
  var factor = (document.getElementById('su').checked == true ? 2 : 1);
  var time   = 0;
  var hold   = 0;

  if (start[0] != ziel[0]) {
    enf = 20000 * Math.abs(start[0] - ziel[0]);
  } else {
    if (start[1] != ziel[1]) {
      enf = 95 * Math.abs(start[1] - ziel[1]) + 2700;
    } else {
      if (start[2] != ziel[2]) {
        enf = 5 * Math.abs(start[2] - ziel[2]) + 1000;
      } else {
        enf = 5;
      }
    }
  }

  //Berechnung - Anzahl/Lagerkapazit�t
  var i;
  for (i = 201; i < 202+data2.length; i++) {
    if (i != 210) {
      if (document.getElementById('i' + i).value > 0) spd = Math.min(spd,retint(document.getElementById('s' + i).firstChild.nodeValue));
      anz = anz - -1 * document.getElementById('i' + i).value;
      lag = lag - -1 * retint(document.getElementById('l' + i).firstChild.nodeValue);
    }
  }

  var time2 = Math.round(time = (10+ (350 / document.getElementById('sel2').value * Math.sqrt(enf*1000/spd))));
  time = Math.round(time / factor);

  //Berechnung - Treibstoff
  var verbrauch = 0;
  var gesverbrauch = 0;
  var shipspd = 0;
  var spd2 = 0;
  var num = 0;
  for (i = 0; i < data2.length; i++) {
    num = document.getElementById('i'+data2[i][0]).value;
    if (num != 0) {
      shipspd = retint(document.getElementById('s'+data2[i][0]).firstChild.nodeValue);
      spd2 = 35000 / ( time2 - 10 ) * Math.sqrt( enf * 10 / shipspd );
      verbrauch = num * (data2[i][4] + (data2[i][0] == 201 && document.getElementById('imp').value > 4 ? 10 : 0));
      gesverbrauch += verbrauch * enf / 35000 * Math.pow(spd2 / 10 + 1 , 2);
    }
  }

  document.getElementById('verbrauch').innerHTML = (anz == 0) ? '-' : pc(Math.round(gesverbrauch) + 1);

  document.getElementById('iges').firstChild.nodeValue = pc(anz);
  document.getElementById('lges').firstChild.nodeValue = pc(lag);
  document.getElementById('sges').firstChild.nodeValue = (spd == 110000000000) ? '-' : pc(spd);
  document.getElementById('distance').firstChild.nodeValue = pc(enf);
  document.getElementById('dauer').firstChild.nodeValue = (anz == 0) ? '-' : duration(time);
}

function duration(timex) {
  var ts = Math.round(mod(timex,60));
  if (ts < 10) ts = '0' + ts;
  timex = div(timex,60);
  var tm = mod(timex,60);
  if (tm < 10) tm = '0' + tm;
  var th = div(timex,60);
  if (th < 10) th = '0' + th;
  return th + ':' + tm + ':' + ts
}

  </script>
      <div class="hr">Flight Time Calculator</div>
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td style="width:355px;"><table border="0"   style="text-align:center;" class="t">
            <tr>
              <td>Start</td>
              <td style="width:225px;"><input id="st_gal" type="text" maxlength="3" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();">
                <input id="st_sys" type="text" maxlength="4" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();">
                <input id="st_pla" type="text" maxlength="3" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();"></td>
            </tr>
            <tr>
              <td>Destination</td>
              <td nowrap><input id="zi_gal" type="text" maxlength="3" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();">
                <input id="zi_sys" type="text" maxlength="4" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();">
                <input id="zi_pla" type="text" maxlength="3" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();">
                <select name="sel" id="sel" onChange="berechne()">
                  <option value="planet">Planet</option>
                  <option value="tf">DF</option>
                  <option value="mond">Moon</option>
                </select></td>
            </tr>
            <tr>
              <td>Speed</td>
              <td><select name="sel2" id="sel2" onChange="berechne()">
                <option value="1">100%</option>
                <option value=".9">90%</option>
                <option value=".8">80%</option>
                <option value=".7">70%</option>
                <option value=".6">60%</option>
                <option value=".5">50%</option>
                <option value=".4">40%</option>
                <option value=".3">30%</option>
                <option value=".2">20%</option>
                <option value=".1">10%</option>
              </select>
                <span title="Speed universe">
                  <input type="checkbox" id="su" value="" onClick="berechne()">
                  <label for="su">x2</label>
                </span></td>
            </tr>
            <tr>
              <td>Distance</td>
              <td><span id="distance">5</span></td>
            </tr>
            <tr>
              <td>Duration (one way)</td>
              <td><span id="dauer">-</span></td>
            </tr>
            <tr>
              <td>Fuel Consumption</td>
              <td><span id="verbrauch">-</span></td>
            </tr>
          </table></td>
          <td style="text-align:left;vertical-align:top;"><table border="0"   style="text-align:center;" class="t">
            <tr>
              <td style="width:150px;">Jet Drive</td>
              <td style="width:125px;"><input id="vbt" maxlength="2" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('-1')"></td>
            </tr>
            <tr>
              <td>Pulse Drive</td>
              <td><input id="imp" maxlength="2" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('-1')"></td>
            </tr>
            <tr>
              <td>Warp Drive</td>
              <td><input id="ha" maxlength="2" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('-1')"></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <br>
      <table border="0"   class="t">
        <tr class="light">
          <td style="width:115px;">Ship</td>
          <td>Count</td>
          <td style="width:115px;">Storage Capacity</td>
          <td style="width:115px;">Speed</td>
        </tr>
        <tr>
          <td><img src="images/atlas_class.png" /> Atlas</td>
          <td><input class="n" id="i201" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('201')"></td>
          <td><span id="l201">0</span></td>
          <td><span id="s201">5.000</span></td>
        </tr>
        <tr>
          <td><img src="images/hercules_class.png" /> Hercules</td>
          <td><input class="n" id="i202" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('202')"></td>
          <td><span id="l202">0</span></td>
          <td><span id="s202">7.500</span></td>
        </tr>
        <tr>
          <td><img src="images/artemis_class.png" /> Artemis</td>
          <td><input class="n" id="i203" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('203')"></td>
          <td><span id="l203">0</span></td>
          <td><span id="s203">12.500</span></td>
        </tr>
        <tr>
          <td><img src="images/apollo_class.png" /> Apollo</td>
          <td><input class="n" id="i204" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('204')"></td>
          <td><span id="l204">0</span></td>
          <td><span id="s204">10.000</span></td>
        </tr>
        <tr>
          <td><img src="images/poseidon_class.png" /> Poseidon</td>
          <td><input class="n" id="i205" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('205')"></td>
          <td><span id="l205">0</span></td>
          <td><span id="s205">15.000</span></td>
        </tr>
        <tr>
          <td><img src="images/athena_class.png" /> Athena</td>
          <td><input class="n" id="i206" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('206')"></td>
          <td><span id="l206">0</span></td>
          <td><span id="s206">10.000</span></td>
        </tr>
        <tr>
          <td><img src="images/gaia_class.png" /> Gaia</td>
          <td><input class="n" id="i207" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('207')"></td>
          <td><span id="l207">0</span></td>
          <td><span id="s207">2.500</span></td>
        </tr>
        <tr>
          <td><img src="images/dionysus_class.png" /> Dionysus</td>
          <td><input class="n" id="i208" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('208')"></td>
          <td><span id="l208">0</span></td>
          <td><span id="s208">2.000</span></td>
        </tr>
        <tr>
          <td><img src="images/hermes_class.png" /> Hermes</td>
          <td><input class="n" id="i209" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('209')"></td>
          <td><span id="l209">0</span></td>
          <td><span id="s209">100.000.000</span></td>
        </tr>
        <tr>
          <td><img src="images/ares_class.png" /> Ares</td>
          <td><input class="n" id="i211" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('211')"></td>
          <td><span id="l211">0</span></td>
          <td><span id="s211">4.000</span></td>
        </tr>
        <tr>
          <td><img src="images/prometheus_class.png" /> Prometheus</td>
          <td><input class="n" id="i212" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('212')"></td>
          <td><span id="l212">0</span></td>
          <td><span id="s212">5.000</span></td>
        </tr>
        <tr>
          <td><img src="images/zeus_class.png" /> Zeus</td>
          <td><input class="n" id="i213" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('213')"></td>
          <td><span id="l213">0</span></td>
          <td><span id="s213">100</span></td>
        </tr>
        <tr>
          <td><img src="images/hades_class.png" /> Hades</td>
          <td><input class="n" id="i214" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('214')"></td>
          <td><span id="l214">0</span></td>
          <td><span id="s214">10.000</span></td>
        </tr>
        <tr>
          <td><img src="images/hephaestus_class.png" /> Hephaestus</td>
          <td><input class="n" id="i215" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('215')"></td>
          <td><span id="l215">0</span></td>
          <td><span id="s215">90</span></td>
        </tr>
        <tr class="space">
          <td colspan="4"></td>
        </tr>
        <tr>
          <td>Total</td>
          <td><span id="iges">0</span></td>
          <td><span id="lges">0</span></td>
          <td><span id="sges">-</span></td>
        </tr>
      </table>
     
      <!-- = = = = = site content end = = = = = --></td>
  </tr>
  <tr></tr>
</table>
