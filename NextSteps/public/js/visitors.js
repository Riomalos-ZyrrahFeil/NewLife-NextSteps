function openAssignModal(id, name, currentVolunteerId) {
  document.getElementById('modalVisitorId').value = id;
  document.getElementById('modalVisitorName').innerText = 'Managing: ' + name;
  
  const unassignBtn = document.getElementById('btnUnassign');
  if (unassignBtn) 
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
  if (query.length < 2) {
    document.getElementById('volunteerList').innerHTML = '';
    return;
  }

  fetch(`/admin/volunteers/search?q=${encodeURIComponent(query)}`)
    .then(res => res.json())
    .then(data => {
      let list = document.getElementById('volunteerList');
      list.innerHTML = data.map(v => `
        <li class="volunteer-item" onclick="assignTo(${v.user_id})">
          <div class="visitor-name">${v.full_name}</div>
          <div class="visitor-subtext">Active Volunteer</div>
        </li>
      `).join('');
    });
}

function assignTo(volunteerId) {
  const visitorId = document.getElementById('modalVisitorId').value;
  const token = document.querySelector('meta[name="csrf-token"]').content;

  fetch('/admin/visitors/assign', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token
    },
    body: JSON.stringify({ 
      visitor_id: visitorId, 
      user_id: volunteerId
    })
  }).then(res => {
    if (res.ok) window.location.reload();
  });
}