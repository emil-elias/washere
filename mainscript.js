//get search parameters
const urlParams = new URLSearchParams(window.location.search);
const withParam = urlParams.get('w');
const yearParam = urlParams.get('y');

var queries = "";

if (withParam) {
  queries += `?w=${encodeURIComponent(withParam)}`;
}

if (yearParam) {
  queries += withParam ? `&y=${encodeURIComponent(yearParam)}` : `?y=${encodeURIComponent(yearParam)}`;
}

var map = L.map("map", {
  attributionControl: false,
  worldCopyJump: true,
  minZoom: 3,
  maxBounds: [
    [-90, -Infinity],
    [90, Infinity],
  ],
}).setView([53.07724756278127, 8.801302178162215], 3);

/*L.control
  .attribution({
    position: "topright"
  })
  .addTo(map);*/

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  /*attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',*/
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

fetch(`./api/getplaces.php${queries}`)
  .then((response) => response.json())
  .then((data) => {
    places = data;

    places.forEach((place) => {
      if (!place.lat || !place.lon) return;

      let nameString = place.name_native != "" ? `${place.name_native}<br>` : "";
      nameString += place.category_id >= 40 && place.category_id < 50 ? `› ${place.name} ‹` : place.name;
      let suffixString = place.suffix != "" ? `<span class="detail-text"> ${place.suffix}</span>` : "";
      let akaString = place.aka != "" ? `<i>${place.aka}</i>` : ``;
      //let capitalString = place.category.includes("capital") ? " (Capital)" : ""; 
      let capitalString = "";
      let populationString = place.population != "0" ? `<br>Population: ${Number(place.population).toLocaleString('en-US')}` : "";
      let dateString = place.date != "" ? `<br>${place.status}: ${place.date}` : "";
      let descString = place.html_desc != "" ? `<br><hr style="margin-top: 1rem; margin-bottom: 1rem; border: none; border-top: 1px solid #333" />${place.html_desc}` : "";

      if (populationString != "" && place.population_metro != "0") {
        populationString += ` (${Number(place.population_metro).toLocaleString('en-US')})`;
      }

      let icon = L.icon({
        iconUrl: determineIcon(parseInt(place.category_id)),
        iconAnchor: [9.5, 9.5],
        popupAnchor: [0, 0],
        shadowSize: [50, 50],
        shadowAnchor: [9.5, 9.5],
        iconSize: determineIconSize(parseInt(place.category_id)),
      });


      let marker = L.marker([place.lat, place.lon], { icon: icon }).addTo(map);

      if (!place.status_id.endsWith("1") && !place.status_id.endsWith("0")) {
        marker.setOpacity(0.5);
      }

      if (place.image_url && place.image_url.trim() != "") {
        marker.bindPopup(
          `<img src='${place.image_url}' onclick='showImg("${place.image_url}")'>
          <div class='popup-textcontent' style='min-width: 15rem; max-width: 15rem'>
          <h3>${nameString}${suffixString}</h3>
          ${akaString}
          <hr style="margin-top: 1rem; margin-bottom: 1rem; border: none; border-top: 1px solid #333" />
          Country: ${place.country}${capitalString}
          ${populationString}
          ${dateString}
          ${descString}
          </div>`
        );
      } else {
        marker.bindPopup(
          `<div class='popup-textcontent'>
          <h3>${nameString}${suffixString}</h3>
          ${akaString}
          <hr style="margin-top: 1rem; margin-bottom: 1rem; border: none; border-top: 1px solid #333" />
          Country: ${place.country}${capitalString}
          ${populationString}
          ${dateString}
          ${descString}
          </div>`
        );
      }
    });

    document.getElementById("loader-container").style.display = "none";
  })
  .catch((error) => console.log(error));

fetch(`./api/stats/total.php${queries}`)
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
    document.getElementById("major_big_places").innerText = data.major_big_places;
    document.getElementById("special_places").innerText = data.special_places;
    document.getElementById("capitals").innerText = data.capitals;
    document.getElementById("terminals").innerText = data.terminals;
    document.getElementById("parks").innerText = data.parks;
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

function determineIcon(category_id) {
  if (category_id) {
    switch (category_id) {
      case 2:
      case 12:
        return "images/diamond-fill-white.svg";
      case 1:
      case 0:
      case 11:
      case 10:
        return "images/diamond-fill-gold.svg";
      case 22:
        return "images/diamond-half-white.svg";
      case 21:
      case 20:
        return "images/diamond-half-gold.svg";
      case 32:
        return "images/diamond-white.svg";
      case 30:
        return "images/diamond-gold.svg";
      case 42: 
        return "images/square-white.svg";
      case 40: 
        return "images/square-gold.svg";
      case 52:
        return "images/circle-white.svg";
      case 50:
        return "images/circle-gold.svg";
      default:
        return "images/diamond-white.svg";
    }

  }

  return "images/diamond-white.svg";
}

function determineIconSize(category_id) {
  if (category_id && category_id < 10) {
    return [20, 20];
  } else if (category_id && category_id >= 10 && category_id < 20) {
    return [17.5, 17.5];
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
