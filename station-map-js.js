(function ($) {
  const map = L.map("map_station_map").setView([38.013, 142.251], 5);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);

  var mcIcon = L.Icon.extend({
    options: {
      iconUrl: map_station_url + "MCpin.png",
      iconSize: [32, 52],
      iconAnchor: [16, 52],
      popupAnchor: [-3, -55],
    },
  });

  function ajax_get_map_stations(callback) {
    $.ajax({
      type: "GET",
      url:
        "/wp-admin/admin-ajax.php?order=" +
        map_station_order +
        "&orderby=" +
        map_station_orderby,
      data: {
        action: "get_station_posts",
      },

      success: function (response) {
        callback(response);
      },
    });
  }

  $(function () {
    $(".map_station_mini_map").each(function (index) {
      let map_station_sec = $(this).parent().parent();

      let mini_map = L.map($(this).attr("id"), { zoomControl: false });
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "",
      }).addTo(mini_map);
      mini_map.setView(
        [$(map_station_sec).data("lat"), $(map_station_sec).data("lng")],
        15
      );
      L.marker(
        [$(map_station_sec).data("lat"), $(map_station_sec).data("lng")],
        {
          icon: new mcIcon(),
        }
      ).addTo(mini_map);

      L.marker(
        [$(map_station_sec).data("lat"), $(map_station_sec).data("lng")],
        {
          icon: new mcIcon(),
        }
      )
        .bindPopup(
          `<b>${$(map_station_sec).data("title")}</b>
                          <br />${$(map_station_sec).data("information")}<br />
                          Tel: ${$(map_station_sec).data("telephone")}<br />
                          <a href='${$(map_station_sec).data(
                            "homepage"
                          )}' target='_blank'>${$(map_station_sec)
            .data("homepage")
            .replace(/(^\w+:|^)\/\//, "")}</a><br />
                          <a href='https://maps.google.de/maps?q=${$(
                            map_station_sec
                          ).data("lat")},${$(map_station_sec).data(
            "lng"
          )}' target='_blank'>Show in Google Maps</a>
                      `
        )
        .addTo(map);
    });
  });
})(jQuery);
