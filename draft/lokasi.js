window.addEventListener("load", () => {
    const allowLocationButton = document.querySelector(
      "#allow-location-button"
    );
    const myLocation = document.querySelector("#my-location");
    const ourDistance = document.querySelector("#our-distance");
    const yourLatitude = document.querySelector("#your-latitude");
    const yourLongitude = document.querySelector("#your-longitude");
    const yourLocation = document.querySelector("#your-location");
    
    const myLat = "-6.499865275377606";
    const myLon = "108.36054212092465";
    
    function isSupportLocation() {
      if (navigator.geolocation) {
        //allowLocationButton.classList.remove("d-none");

          navigator.geolocation.getCurrentPosition(showPosition, (err) => {
            switch (err.code) {
              case 1:
                swal({
                  title: "Gagal",
                  text: "Anda tidak mengizinkan lokasi.",
                  icon: "error",
                });
                allowLocationButton.setAttribute("disabled", true);
                break;
              default:
                break;
            }
          });
      } else {
        swal({
          title: "Gagal",
          text: "browser ini tidak mendukung akses lokasi.",
          icon: "error",
        });
      }
    }

    function showPosition(position) {
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;

      const apiUrl = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=id`;

      fetch(apiUrl, { headers: { "Content-Type": "application/json" } })
        .then((res) => res.json())
        .then((res) => {
          console.log(res);
          const city = res.city === "" ? "" : res.city + ", ";
          const provinsi = res.principalSubdivision === "" ? "" : res.principalSubdivision + ", ";
          const negara =
            res.countryName === "" ? "" : " " + res.countryName;

          yourLatitude.innerText = res.latitude;
          yourLongitude.innerText = res.longitude;
          yourLocation.innerText = `${city}${provinsi}${negara}`;

          const userLat = res.latitude;
          const userLon = res.longitude;

          calculateDistance(userLat, userLon);
        });
    }

    function calculateDistance(userLat, userLon) {
      const apiUrl = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${myLat}&longitude=${myLon}&localityLanguage=id`;

      fetch(apiUrl, { headers: { "Content-Type": "application/json" } })
        .then((res) => res.json())
        .then((res) => {
          myLocation.innerText = `${res.locality}, ${res.principalSubdivision}, ${res.countryName}`;
        });

      const R = 6371e3; // metres
      const φ1 = (userLat * Math.PI) / 180; // φ, λ in radians
      const φ2 = (myLat * Math.PI) / 180;
      const Δφ = ((myLat - userLat) * Math.PI) / 180;
      const Δλ = ((myLon - userLon) * Math.PI) / 180;

      const a =
        Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
        Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

      const d = R * c; // in metres
      const distance = d.toFixed(0);

      const distanceInKm = distance / 1000;

      console.log(Intl.NumberFormat().format(distanceInKm) + " kilometer");

      ourDistance.innerText = Intl.NumberFormat('id-ID', {minimumFractionDigits: 3}).format(distanceInKm) + " kilometer";
    }

    isSupportLocation();
  });