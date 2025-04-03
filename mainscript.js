var map = L.map("map", {
  attributionControl: false,
  worldCopyJump: true,
  minZoom: 3,
  maxBounds: [
    [-90, -Infinity],
    [90, Infinity],
  ],
}).setView([53.07724756278127, 8.801302178162215], 3);

L.control
  .attribution({
    position: "topright"
  })
  .addTo(map);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
  opacity: 0.5,
  className: "map",
}).addTo(map);

fetch("./api/getcountries.php")
  .then((response) => response.json())
  .then((data) => {
    let countries = data;

    countries.forEach((country) => {
      fetch(
        `./countries/${country.name.toLowerCase().replace(/\s+/g, "")}.json`
      )
        .then((response) => response.json())
        .then((data) => {
          L.geoJSON(data, {
            style: {
              color: determineCountryColor(country),
              dashArray: parseInt(country.valid) ? null : "5, 10",
              fillOpacity: parseInt(country.valid) ? 0.1 : 0.01,
              opacity: parseInt(country.valid) ? 1 : 0.5,
            },
          }).addTo(map);
        })
        .catch((error) => console.log(error));
    });
  })
  .catch((error) => console.log(error));

/*fetch(`./countries/germany.json`)
  .then((response) => response.json())
  .then((data) => {
    L.geoJSON(data, {
      style: {
        color: "blue",
        dashArray: null,
        fillOpacity: 0.1,
        opacity:1,
      },
    }).addTo(map);
  })
  .catch((error) => console.log(error));*/

fetch("./api/getplaces.php")
  .then((response) => response.json())
  .then((data) => {
    places = data;

    places.forEach((place) => {
      if (!place.lat || !place.lon) return;

      let icon = L.icon({
        iconUrl: determineIcon(place.category),
        iconAnchor: [9.5, 9.5],
        popupAnchor: [0, 0],
        shadowSize: [50, 50],
        shadowAnchor: [9.5, 9.5],
        iconSize: determineIconSize(place.category),
      });

      let marker = L.marker([place.lat, place.lon], { icon: icon }).addTo(map);

      if (place.image_url && place.image_url.trim() != "") {
        marker.bindPopup(
          `<img src='${place.image_url}' onclick='showImg("${place.image_url}")'><div class='popup-textcontent' style='min-width: 15rem'><h3>${place.name}</h3>${place.html_desc}</div>`
        );
      } else {
        marker.bindPopup(
          `<div class='popup-textcontent'><h3>${place.name}</h3>${place.html_desc}</div>`
        );
      }
    });

    document.getElementById("loader-container").style.display = "none";
  })
  .catch((error) => console.log(error));

fetch("./api/stats/total.php")
  .then((response) => response.json())
  .then((data) => {
    data;
    document.getElementById("total_countries").innerHTML = data.total_countries;
    document.getElementById("total_places").innerHTML = data.total_places;
    document.getElementById("european_countries").innerText =
      data.european_countries;
    document.getElementById("asian_countries").innerText = data.asian_countries;
    document.getElementById("invalid_countries").innerText =
      data.invalid_countries;
    document.getElementById("rural_places").innerText = data.rural_places;
    document.getElementById("minor_places").innerText = data.minor_places;
    document.getElementById("major_places").innerText = data.major_places;
    document.getElementById("special_places").innerText = data.special_places;
    document.getElementById("capitals").innerText = data.capitals;
  })
  .catch((error) => console.log(error));

function toggleLegend() {
  let legend = document.getElementById("legend-content");
  let legend2 = document.getElementById("legend");
  let chevron = document.getElementById("chevron");
  if (legend.style.display == "none") {
    legend.style.display = "block";
    //chevron.src = "images/arrow-down-left.svg";
    chevron.classList.remove("rotate");
  } else {
    legend.style.display = "none";
    //chevron.src = "images/arrow-up-right.svg";
    chevron.classList.add("rotate");
  }
}

function determineIcon(category) {
  if (category) {
    switch (category) {
      case "rural":
        return "images/diamond-white.svg";
      case "rural_special":
        return "images/diamond-gold.svg";
      case "minor":
        return "images/diamond-half-white.svg";
      case "minor_special":
        return "images/diamond-half-gold.svg";
      case "minor_capital":
        return "images/diamond-half-gold.svg";
    }

    if (category.includes("major")) {
      if (category.includes("special") || category.includes("capital")) {
        return "images/diamond-fill-gold.svg";
      } else return "images/diamond-fill-white.svg";
    }
  }

  return "images/diamond-white.svg";
}

function determineIconSize(category) {
  if (category && category.includes("big")) {
    return [20, 20];
  } else if (category && category.includes("medium")) {
    return [15, 15];
  } else return [15, 15];
}

function determineCountryColor(country) {
  switch (country.continent) {
    case "Europe":
      return "blue";
    case "Asia":
      return "red";
    default:
      return "white";
  }
}

function showImg(url) {
  console.log("hello");
  let map = document.getElementById("map");
  var container = document.createElement("div");
  container.classList.add("fullscreen-grid");
  var img = document.createElement("img");
  container.appendChild(img);
  img.src = url;
  map.parentNode.insertBefore(container, map);
  //document.body.appendChild(container);

  container.onclick = function () {
    container.remove();
  };

  document.addEventListener("keydown", escHandler);

  function escHandler(event) {
    if (event.key === "Escape") {
      event.preventDefault();
      container.remove();
      document.removeEventListener("keydown", escHandler);
    }
  }
}
