(function ($) {


    function ajax_get_map_stations(callback) {
        $.ajax({
            type: "GET",
            url: "/wp-admin/admin-ajax.php?order=" + map_station_order + "&orderby=" + map_station_orderby,
            data: {
                action: 'get_station_posts'
            },

            success: function (response) {

                callback(response)
            }
        });
    }




    $(function () {
        if ($('#map_station_map').length) {
            ajax_get_map_stations(function (response) {

                response = JSON.parse(response);

                if (response.length) {

                    var map = L.map('map_station_map').setView([38.013, 142.251], 5);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' }).addTo(map);

                    var mcIcon = L.Icon.extend({ options: { iconUrl: map_station_url + 'MCpin.png', iconSize: [32, 52], iconAnchor: [16, 52], popupAnchor: [-3, -55] } });

                    for (let index = 0; index < response.length; index++) {
                        const element = response[index];
                        console.log(element)
                        L.marker(
                            [element?.location?.lat, element?.location?.lng],
                            { icon: new mcIcon() }).bindPopup(
                                `<b>${element['title']}</b>
                                <br />${element['information']}<br />
                                Tel: ${element['telephone']}<br />
                                <a href='${element['homepage']}' target='_blank'>${element['homepage'].replace(/(^\w+:|^)\/\//, '')}</a><br />
                                <a href='https://maps.google.de/maps?q=${element['location']['lat']},${element['location']['lng']}' target='_blank'>Show in Google Maps</a>
                            `).addTo(map);

                    }
                }

            });





        }
    });


})(jQuery)