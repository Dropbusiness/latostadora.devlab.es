/*document.addEventListener("DOMContentLoaded", function() {
    // Verificar si la URL actual contiene "/producto" o el nombre del artista
    var currentURL = window.location.pathname;
    var artistName = `<?=$event['artist_slug']?>` ; // Reemplaza con el nombre del artista

    // Verificar si la URL contiene "/producto" o el nombre del artista
    if (currentURL.includes("/producto") || currentURL.includes("/" + artistName)) {
        // La URL contiene "/producto" o el nombre del artista, por lo que insertamos el contenido en el div con id "prueba"
        insertContent();
    }
    // Agregar un evento de cambio al select para controlar la visibilidad
    var select = document.getElementById("events");
    if (select) {
        select.addEventListener("change", function() {
            // Verificar si se seleccionó una opción en el select
            if (select.value !== "") {
                // Se seleccionó una opción, por lo que mostramos el div con la clase "row"
                insertContent();
            }
        });
    }
});

function insertContent() {
    // Obtén una referencia al div con id "prueba"
    var pruebaDiv = document.getElementById("prueba");

    // Verifica si el elemento con id "prueba" existe
    if (pruebaDiv) {
        // Crea el nuevo contenido que deseas insertar
        var newContent = `
            <!-- Aquí coloca el contenido que deseas insertar -->
            <div class="row">
                <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h2 class="m-0 title-catalogo"><?=$event['artist_name'].' '.$event['tour_name'].' '.$event['city']?> <?=date("d/m/Y", strtotime($event['date']))?> </h2>
                    <select id="events" class="form-select select-date form-select-lg" aria-label=".form-select-lg" style="max-width: 276px; font-size: 18px">
                        <?php foreach ($events as $item) {
                            $href = getenv('app.baseURL') . $item['artist_slug'] . "/" . $item['tour_slug'] . "/" . $item['slug'] . "/" . $item['date'];
                        ?>
                            <option value="<?=$item['id']?>" data-href="<?=$href?>" <?=($event['id'] == $item['id'] ? 'selected' : '')?>><?=strtoupper($item['city'])?> - <?=date("d/m/Y", strtotime($item['date']))?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <!-- Fin del contenido que deseas insertar -->
        `;

        // Inserta el nuevo contenido en el div con id "prueba"
        pruebaDiv.innerHTML = newContent;
    }
}*/
document.addEventListener("DOMContentLoaded", function() {
    // Verificar si existe un div con id "banner2"
    var banner2 = document.getElementById("banner2");

    // Verificar si se encontró el div
    if (banner2) {
        // Crear el nuevo contenido a insertar
        var newContent = `
            <div class="row">
                <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h2 class="m-0 title-catalogo">${eventData.artistName} - ${eventData.tourName} - ${eventData.city} - ${eventData.date} </h2>
                    <select id="events" class="form-select select-date form-select-lg" aria-label=".form-select-lg" style="max-width: 249px; font-size: 16px">
                    ${eventData.events.map(event => `
                    <option value="${event.id}" data-href="${event.href}" ${event.id === eventData.selectedEventId ? 'selected' : ''}>
                        ${event.city} - ${event.date}
                    </option>
                `).join('')}
                    </select>
                </div>
            </div>
        `;

        // Insertar el nuevo contenido en el div "banner2"
        banner2.innerHTML = newContent;
    }
});