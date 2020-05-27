<script type="text/javascript">
  $(document).ready(function()
  {
    nombre_final = document.getElementById("armar-titular-destino").value;
    apellidos = document.getElementById("armar-titular-apellidos").value;
    nombres = document.getElementById("armar-titular-nombres").value;

    $('#armar-titular-nombres').keyup(function()
    {
      nombres_1 = this.value;
      nombres = nombres_1.toLowerCase().replace(/^[\u00C0-\u1FFF\u2C00-\uD7FF\w]|\s[\u00C0-\u1FFF\u2C00-\uD7FF\w]/g, function(letter)
      {
        return letter.toUpperCase();
      });

      nombre_final = nombres + " " + apellidos;
      document.getElementById("armar-titular-destino").setAttribute('value', nombre_final);

    });

    $('#armar-titular-apellidos').keyup(function()
    {
      apellidos1 = this.value;
      apellidos = apellidos1.toLowerCase().replace(/^[\u00C0-\u1FFF\u2C00-\uD7FF\w]|\s[\u00C0-\u1FFF\u2C00-\uD7FF\w]/g, function(letter)
      {
        return letter.toUpperCase();
      });

      nombre_final = nombres + " " + apellidos;
      document.getElementById("armar-titular-destino").setAttribute('value', nombre_final);

    });
  });

</script>
