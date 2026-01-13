function openAssignModal(id, name, currentVolunteerId) {
  document.getElementById('modalVisitorId').value = id;
  document.getElementById('modalVisitorName').innerText = 'Managing: ' + name;
  
  const unassignBtn = document.getElementById('btnUnassign');
  if (unassignBtn) {
    unassignBtn.style.display = currentVolunteerId ? 'block' : 'none';
  }
  document.getElementById('assignModal').style.display = 'flex';
  document.getElementById('volunteerSearch').focus();
}

function closeAssignModal() {
  document.getElementById('assignModal').style.display = 'none';
  document.getElementById('volunteerList').innerHTML = '';
  document.getElementById('volunteerSearch').value = '';
}

function searchVolunteers() {
  let query = document.getElementById('volunteerSearch').value;
  const loader = document.getElementById('searchLoader');

  if (query.length < 2) {
    document.getElementById('volunteerList').innerHTML = '';
    return;
  }

  if (loader) loader.style.display = 'block';

  fetch(`/admin/volunteers/search?q=${encodeURIComponent(query)}`)
    .then(res => res.json())
    .then(data => {
      if (loader) loader.style.display = 'none';
      let list = document.getElementById('volunteerList');
      list.innerHTML = data.map(v => `
        <li class="volunteer-item" onclick="assignTo(${v.user_id})">
          <div class="visitor-name">${v.full_name}</div>
          <div class="visitor-subtext">Active Volunteer</div>
        </li>
      `).join('');
    });
}

function assignTo(userId) {
  const visitorId = document.getElementById('modalVisitorId').value;

  if (!userId && userId !== null) {
    alert("Please select a volunteer first.");
    return;
  }

  fetch("/admin/visitors/assign", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
      visitor_id: visitorId,
      user_id: userId
    })
  })
  .then(res => {
    if (res.status === 400) throw new Error("No user selected");
    return res.json();
  })
  .then(data => {
    if (data.success) location.reload();
  })
  .catch(err => console.error("Assignment Error:", err.message));
}