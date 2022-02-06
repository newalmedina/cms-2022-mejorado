<div>
    <div class="row">
        <x-info-box id="totalUsuarios" color="bg-info" value="0" icon="fas fa-user">
            Total de usuarios
        </x-info-box>
        <x-info-box id="nuevosUsuarios" color="bg-success" value="0" icon="fas fa-users">
            Nuevos usuarios últimos 30 días
        </x-info-box>
        <x-info-box id="activosUsuarios" color="bg-warning" value="0" icon="fas fa-user-plus">
            Usuarios activos en la última hora
        </x-info-box>
    </div>
</div>


<!-- page script -->
<script defer type="text/javascript">
    window.addEventListener("load", function(event) {
        getStatsData();
    });


    function getStatsData() {

        axios.get('/admin/users/userStats').then(res => {

                let results = res.data;

                $('#totalUsuarios').html(results.total);
                $('#nuevosUsuarios').html(results.nuevos);
                $('#activosUsuarios').html(results.activos);

            })
            .catch(function(error) {
                // handle error
                console.log(error);
            })
            .then(function() {
                // always executed
            });

    }
</script>
