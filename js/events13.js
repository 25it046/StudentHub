
alert("JavaScript is working!");
const api = "../php/events_api13.php";

async function loadEvents() {
    const response = await fetch(api);
    const events = await response.json();
    const list = document.getElementById("eventList");
    list.innerHTML = "";

    events.forEach(event => {
        const row = document.createElement("tr");

        [event.event_id, event.event_name, event.description, event.event_date].forEach(value => {
            const cell = document.createElement("td");
            cell.textContent = value ?? "";
            row.appendChild(cell);
        });

        const actions = document.createElement("td");
        const edit = document.createElement("button");
        edit.textContent = "Edit";
        edit.onclick = () => editEvent(event);

        const del = document.createElement("button");
        del.textContent = "Delete";
        del.onclick = () => deleteEvent(event.event_id);

        actions.append(edit, del);
        row.appendChild(actions);
        list.appendChild(row);
    });
}

document.getElementById("eventForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const id = document.getElementById("event_id").value;
    const data = {
        event_name: document.getElementById("event_name").value,
        description: document.getElementById("description").value,
        event_date: document.getElementById("event_date").value
    };

    if (id) data.event_id = Number(id);

    const response = await fetch(api, {
        method: id ? "PUT" : "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(data)
    });

    const result = await response.json();
    document.getElementById("message").textContent = result.message;

    if (result.success) {
        resetForm();
        await loadEvents();
    }
});

function editEvent(event) {
    document.getElementById("event_id").value = event.event_id;
    document.getElementById("event_name").value = event.event_name;
    document.getElementById("description").value = event.description ?? "";
    document.getElementById("event_date").value = event.event_date;
}

async function deleteEvent(id) {
    if (!confirm("Delete this event?")) return;

    const response = await fetch(api, {
        method: "DELETE",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({event_id: Number(id)})
    });

    const result = await response.json();
    document.getElementById("message").textContent = result.message;

    if (result.success) await loadEvents();
}

function resetForm() {
    document.getElementById("eventForm").reset();
    document.getElementById("event_id").value = "";
}

loadEvents();
