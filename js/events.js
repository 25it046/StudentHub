let events = [];
let currentPage = 1;
let perPage = 4;

let search = document.getElementById("search");
let filter = document.getElementById("filter");
let sort = document.getElementById("sort");
let eventList = document.getElementById("eventList");
let message = document.getElementById("message");

fetch("../data/events.json")
    .then(response => {
        if (!response.ok) {
            throw new Error("Unable to load events");
        }
        return response.json();
    })
    .then(data => {
        events = data;
        displayEvents();
    })
    .catch(() => {
        message.textContent = "Unable to load events. Please use Live Server.";
    });

function displayEvents() {
    let searchText = search.value.toLowerCase();

    let result = events.filter(event =>
        event.name.toLowerCase().includes(searchText) &&
        (filter.value === "All" || event.category === filter.value)
    );

    result.sort((a, b) => {
        if (sort.value === "category") {
            return a.category.localeCompare(b.category);
        }
        return a.name.localeCompare(b.name);
    });

    let totalPages = Math.max(1, Math.ceil(result.length / perPage));

    if (currentPage > totalPages) {
        currentPage = totalPages;
    }

    let start = (currentPage - 1) * perPage;
    let pageEvents = result.slice(start, start + perPage);

    eventList.innerHTML = "";

    if (pageEvents.length === 0) {
        message.textContent = "No events found.";
    } else {
        message.textContent = "Total events found: " + result.length;

        pageEvents.forEach(event => {
            let card = document.createElement("div");
            card.className = "event-card";

            let title = document.createElement("h3");
            title.textContent = event.name;

            let category = document.createElement("p");
            category.textContent = "Category: " + event.category;

            let location = document.createElement("p");
            location.textContent = "Location: " + event.location;

            card.append(title, category, location);
            eventList.appendChild(card);
        });
    }

    document.getElementById("pageNumber").textContent =
        "Page " + currentPage + " of " + totalPages;

    document.getElementById("prev").disabled = currentPage === 1;
    document.getElementById("next").disabled =
        currentPage === totalPages || result.length === 0;
}

search.addEventListener("input", function() {
    currentPage = 1;
    displayEvents();
});

filter.addEventListener("change", function() {
    currentPage = 1;
    displayEvents();
});

sort.addEventListener("change", function() {
    currentPage = 1;
    displayEvents();
});

document.getElementById("prev").addEventListener("click", function() {
    if (currentPage > 1) {
        currentPage--;
        displayEvents();
    }
});

document.getElementById("next").addEventListener("click", function() {
    currentPage++;
    displayEvents();
});