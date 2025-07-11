$(function () {
  "use strict";

  // Chart 1
  var ctx = document.getElementById("chart1");
  if (ctx) {
    ctx = ctx.getContext("2d");
    var myChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: [
          "Jan",
          "Feb",
          "Mar",
          "Apr",
          "May",
          "Jun",
          "Jul",
          "Aug",
          "Sep",
          "Oct",
          "Nov",
          "Dec",
        ],
        datasets: [
          {
            label: "Pemesanan Barang",
            data: [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65],
            backgroundColor: "#14abef",
            borderColor: "#14abef",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 2,
            fill: false,
          },
          {
            label: "Pemesanan Paket",
            data: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60],
            backgroundColor: "#ffc107",
            borderColor: "#ffc107",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 2,
            fill: false,
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "top",
            display: true,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  // Chart 2 - Pie Chart for Products
  var ctx2 = document.getElementById("chart2");
  if (ctx2) {
    ctx2 = ctx2.getContext("2d");
    var myChart2 = new Chart(ctx2, {
      type: "doughnut",
      data: {
        labels: ["Dekorasi", "Katering", "Fotografi", "Lainnya"],
        datasets: [
          {
            data: [40, 25, 20, 15],
            backgroundColor: ["#14abef", "#02ba5a", "#d13adf", "#fba540"],
            borderWidth: 0,
            hoverOffset: 4,
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
            display: true,
          },
        },
      },
    });
  }

  // Chart 3 - Weekly Revenue
  var ctx3 = document.getElementById("chart3");
  if (ctx3) {
    ctx3 = ctx3.getContext("2d");
    var myChart3 = new Chart(ctx3, {
      type: "bar",
      data: {
        labels: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
        datasets: [
          {
            label: "Total Revenue",
            data: [4, 7, 5, 11, 8, 10, 9],
            backgroundColor: [
              "#14abef",
              "#02ba5a",
              "#d13adf",
              "#fba540",
              "#14abef",
              "#02ba5a",
              "#d13adf",
            ],
            borderColor: [
              "#14abef",
              "#02ba5a",
              "#d13adf",
              "#fba540",
              "#14abef",
              "#02ba5a",
              "#d13adf",
            ],
            borderWidth: 0,
            borderRadius: 20,
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        barPercentage: 0.5,
        categoryPercentage: 0.7,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  // Initialize JVector Maps
  var mapElement = document.getElementById("geographic-map-2");
  if (mapElement && typeof $.fn.vectorMap !== "undefined") {
    $("#geographic-map-2").vectorMap({
      map: "world_mill_en",
      backgroundColor: "transparent",
      borderColor: "#818181",
      borderOpacity: 0.25,
      borderWidth: 1,
      zoomOnScroll: false,
      color: "#009efb",
      regionStyle: {
        initial: {
          fill: "#6c757d",
        },
      },
      markerStyle: {
        initial: {
          r: 9,
          fill: "#fff",
          "fill-opacity": 1,
          stroke: "#000",
          "stroke-width": 5,
          "stroke-opacity": 0.4,
        },
      },
      enableZoom: true,
      hoverColor: "#009efb",
      markers: [
        {
          latLng: [-2.19, 117.38],
          name: "Indonesia",
        },
      ],
      hoverOpacity: null,
      normalizeFunction: "linear",
      scaleColors: ["#b6d6ff", "#005ace"],
      selectedColor: "#c9dfaf",
      selectedRegions: [],
      showTooltip: true,
    });
  }
});
