// Dummy pharmacy data
const pharmacies = [
    {
      name: "HealthPlus Pharmacy",
      location: "University of Ghana Campus",
      hours: "8am - 10pm",
      available: ["Paracetamol", "Vitamin C", "Ibuprofen"],
      emergency: true,
      featured: true,
      illnesses: ["Fever", "Cough", "Headache"]
    },
    {
      name: "MediCare Pharmacy",
      location: "East Legon",
      hours: "24/7",
      available: ["Malaria Tabs", "Thermometers", "Zincovit"],
      emergency: true,
      featured: true,
      illnesses: ["Malaria", "Cold", "Cough"]
    },
    {
      name: "Silverline Pharmacy",
      location: "Madina Zongo Junction",
      hours: "9am - 9pm",
      available: ["Cough Syrup", "First Aid", "Antiseptics"],
      emergency: false,
      featured: false,
      illnesses: ["Cough", "Wound Care"]
    },
    {
      name: "HopeMed Pharmacy",
      location: "Legon Boundary Road",
      hours: "10am - 10pm",
      available: ["Blood Pressure Tabs", "Pain Relievers"],
      emergency: true,
      featured: false,
      illnesses: ["Pain", "High Blood Pressure"]
    }
  ];
  
  // DOM Elements
  const searchInput = document.getElementById("search-input");
  const searchForm = document.getElementById("search-form");
  const categorySelect = document.getElementById("category-select");
  const suggestionsBox = document.getElementById("suggestions-box");
  const resultsContainer = document.getElementById("results-container");
  
  // Live search suggestions for illnesses
  searchInput.addEventListener("input", () => {
    const keyword = searchInput.value.toLowerCase();
    if (!keyword) {
      suggestionsBox.innerHTML = "";
      return;
    }
  
    const matches = pharmacies.filter(p =>
      p.illnesses.some(illness => illness.toLowerCase().includes(keyword))
    );
  
    suggestionsBox.innerHTML = matches.map(p => `<div class="suggestion-item">${p.illnesses.join(", ")}</div>`).join("");
  
    document.querySelectorAll(".suggestion-item").forEach(item => {
      item.onclick = () => {
        searchInput.value = item.textContent;
        suggestionsBox.innerHTML = "";
      };
    });
  });
  
  // Handle search and filtering
  searchForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const keyword = searchInput.value.toLowerCase();
    const category = categorySelect.value;
  
    const filtered = pharmacies.filter(p => {
      const matchesIllness =
        !keyword ||
        p.illnesses.some(illness => illness.toLowerCase().includes(keyword));
  
      const matchesCategory =
        category === "all" ||
        (category === "open" && p.hours.includes("24")) ||
        (category === "emergency" && p.emergency) ||
        (category === "featured" && p.featured);
  
      return matchesIllness && matchesCategory;
    });
  
    renderResults(filtered);
  });
  
  // Render search results
  function renderResults(data) {
    resultsContainer.innerHTML = `<h3>Search Results</h3>`;
    if (data.length === 0) {
      resultsContainer.innerHTML += `<p>No pharmacies match your search.</p>`;
      return;
    }
  
    data.forEach(p => {
      resultsContainer.innerHTML += `
        <div class="pharmacy-card">
          <img src="images/pharmacy1.jpg" alt="${p.name}" />
          <div>
            <h4>${p.name}</h4>
            <p>Location: ${p.location}</p>
            <p>Hours: ${p.hours}</p>
            <p>Available: ${p.available.join(", ")}</p>
            <p><strong>Emergency Delivery:</strong> ${p.emergency ? "✅" : "❌"}</p>
            <p><strong>Illnesses Treated:</strong> ${p.illnesses.join(", ")}</p>
          </div>
        </div>
      `;
    });
  }
  