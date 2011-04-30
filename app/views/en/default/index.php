<style type="text/css">
body {
  font-family: arial, helvetica, sans-serif;
  margin: 0;
  background: url(/images/stripes.png);
}

#footer {
  font-size: 10px;
  color: #999;
  text-align: center;
}

h1 {
  font-style: italic;
  font-size: 28px;
  color: white;
  background-color: red;
  text-align: center;
}

.label {
  text-align: right;
  text-shadow: 0px 1px 1px #fff;
}

.button {
  color: red;
}

.duration {
  width: 50px;
}

.distance {
  width: 50px;
}

.result {
  width: 70px;
  font-weight: bold;
}
</style>

<form name="converter" method="get" action="return false">
<table align="center">
  <tr>
    <td class="label"><label for="hours">Hours</label></td>
    <td><input class="duration" tabindex="1" type="text" name="hours" value="0" onchange="runner.set('hours', this.value)" /> <input type="button" name="hours_up" onclick="runner.up('hours')" value="&uarr;" />  <input type="button" name="hours_down" onclick="runner.down('hours')" value="&darr;" /></td>
  </tr>
  <tr>
    <td class="label"><label for="mins">Minutes</label></td>
    <td><input class="duration" tabindex="2" type="text" name="mins" value="0" onchange="runner.set('mins', this.value)" /> <input type="button" name="mins_up" onclick="runner.up('mins')" value="&uarr;" />  <input type="button" name="mins_down" onclick="runner.down('mins')" value="&darr;" /></td>
  </tr>
  <tr>
    <td class="label"><label for="mi">Miles</label></td>
    <td><input class="distance" tabindex="3" type="text" name="mi" value="0" onchange="runner.convert('mi', 'km')" /> <input type="button" name="mi_up" onclick="runner.up('mi')" value="&uarr;" />  <input type="button" name="mi_down" onclick="runner.down('mi')" value="&darr;" /></td>
  </tr>
  <tr>
    <td class="label"><label for="km">Kilometers</label></td>
    <td><input class="distance" tabindex="4" type="text" name="km" value="0" onchange="runner.convert('km', 'mi')" /> <input type="button" name="km_up" onclick="runner.up('km')" value="&uarr;" />  <input type="button" name="km_down" onclick="runner.down('km')" value="&darr;" /></td>
  </tr>
  <tr>
    <td></td>
    <td><input class="button" tabindex="5" type="button" value="Calculate" onclick="runner.calculate()" /></td>
  </tr>
  <tr>
    <td class="label"><label for="time_per_mi">Time per mile</label></td>
    <td><input class="result" type="text" name="time_per_mi" disabled="disabled" /> <input class="result" type="text" name="speed_mph" disabled="disabled" /></td>
  </tr>
  <tr>
    <td class="label"><label for="time_per_km">Time per km</label></td>
    <td><input class="result" type="text" name="time_per_km" disabled="disabled" /> <input class="result" type="text" name="speed_kph" disabled="disabled" /></td>
  </tr>
  <tr>
    <td class="label"><label for="finish_half">Finish time (half)</label></td>
    <td><input class="result" type="text" name="finish_half" disabled="disabled" /></td>
  </tr>
  <tr>
    <td class="label"><label for="finish_full">Finish time (full)</label></td>
    <td><input class="result" type="text" name="finish_full" disabled="disabled" /></td>
  </tr>
</table>
</form>

<script type="text/javascript">
var runner = {
  hours: 0,
  mins: 0,
  form: null,
  km_per_mi: 1.609344,
  km_per_race: 42.195,
  calculate: function() {
    var mins = this.hours*60 + this.mins;
    var mi = parseFloat(this.form.mi.value);
    var km = parseFloat(this.form.km.value);
    if (mi <= 0 || km < 0) return;
    this.form.time_per_mi.value = this.format_time(mins / mi);
    this.form.time_per_km.value = this.format_time(mins / km);
    if (mins > 0) {
        this.form.speed_mph.value = (parseInt(mi / (mins / 600)) / 10) + ' mph';
        this.form.speed_kph.value = (parseInt(km / (mins / 600)) / 10) + ' kph';
    }
    this.form.finish_half.value = this.format_time(mins / km * this.km_per_race / 2.0);
    this.form.finish_full.value = this.format_time(mins / km * this.km_per_race);
  },
  set: function(field, value) {
    this[field] = parseFloat(value);
    while (field == 'mins' && value >= 60) {
      this.up('hours');
      value = this.change('mins', -60);
    }
    this.calculate();
  },
  up: function(field) {
    this.change(field, 1);
  },
  down: function(field) {
    this.change(field, -1);
  },
  change: function(field, amount) {
    var value = parseInt(this.form[field].value) + amount;
    if (value < 0) value = 0;
    this.form[field].value = value;
    if (field == 'mi') this.convert('mi', 'km');
    else if (field == 'km') this.convert('km', 'mi');
    else this.set(field, value); // set hours or mins
    return value;
  },
  start: function() {
    this.form = document.forms.converter;
  },
  convert: function(from, to) {
    var dist = this.form[from].value;
    if (from == 'mi') dist *= this.km_per_mi
    else dist /= this.km_per_mi;
    dist = parseInt(dist * 1000) / 1000;
    this.form[to].value = dist;
    this.calculate();
  },
  format_time: function(mins) {
    var h = parseInt(mins / 60);
    var m = parseInt(mins - (h*60));
    var s = parseInt((mins - parseInt(mins)) * 60)
    if (h < 10) h = '0' + h;
    if (m < 10) m = '0' + m;
    if (s < 10) s = '0' + s;
    return h + ':' + m + ':' + s;
  },
  version: 1
};
runner.start();
</script>

