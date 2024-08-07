let userIdToDelete = null;
var searchType = document.getElementById('searchType');
var searchValue = document.getElementById('searchValue');
var debounceTimeout;
var spinner = document.getElementById('spinner')


function openSpinner() {
  // Ambil elemen body dari dokumen
  var body = document.getElementsByTagName("body")[0];

  // Buat elemen div untuk spinner
  var spinnerDiv = document.createElement("div");
  spinnerDiv.className = "spinner-opened";

  // Buat elemen div untuk spinner-border
  var spinnerBorderDiv = document.createElement("div");
  spinnerBorderDiv.className = "spinner-border text-light";
  spinnerBorderDiv.setAttribute("role", "status");

  // Buat elemen span untuk teks "Loading..."
  var spinnerText = document.createElement("span");
  spinnerText.className = "visually-hidden";
  spinnerText.innerText = "Loading...";

  // Masukkan elemen span ke dalam elemen spinner-border
  spinnerBorderDiv.appendChild(spinnerText);

  // Masukkan elemen spinner-border ke dalam elemen spinner
  spinnerDiv.appendChild(spinnerBorderDiv);

  // Masukkan elemen spinner ke dalam elemen body
  body.appendChild(spinnerDiv);
}

function showModal(userId) {
  userIdToDelete = userId;
  var myModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'), {
    keyboard: false
  });
  myModal.show();
}

function confirmDelete() {
  if (userIdToDelete !== null) {
    openSpinner();
    window.location.href = 'proses/hapus_user.php?id=' + userIdToDelete;
  }
}

searchType.addEventListener('input', () => {
  if (searchType.value == 'nama') {
    searchValue.setAttribute('placeholder', 'Masukan nama user')
  } else if (searchType.value == 'nomorHP') {
    searchValue.setAttribute('placeholder', 'Masukan nomor HP user')
  }
})

document.addEventListener("DOMContentLoaded", function () {
  getUserData();
});

document.getElementById('searchValue').addEventListener('input', function () {
  getUserData();
});

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById('buttonAlert').click();
});


function getUserData() {
  clearTimeout(debounceTimeout);

  debounceTimeout = setTimeout(() => {

    //event.preventDefault(); // Mencegah pengiriman formulir secara default

    const searchType = document.getElementById('searchType').value;
    const searchValue = document.getElementById('searchValue').value;
    const userTable = document.getElementById('userTable');

    spinner.setAttribute('style', 'z-index: 2')

    fetch('proses/searchUser.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          searchType: searchType,
          searchValue: searchValue
        })
      })
      .then(response => response.json())
      .then(data => {
        userTable.innerHTML = ''; // Kosongkan tabel
        spinner.setAttribute('style', 'z-index: 0;')
        if (data.length > 0) {
          data.forEach((user, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `<th class="col-1 text-center" scope="row">${index+1}</th>
                  <td class="col-2">${user.nama}</td>
                  <td class="col-2 text-center">${user.nomorHP}</td>
                  <td class="col-1 justify-content-center">
                    <div class="mx-auto" style="width: min-content;">
                      <a href="kesehatan_user.php?id=${user.id}" onclick="openSpinner()" style="width: 27px">
                        <button type="button" class="p-0 btn btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-lg" viewBox="0 0 16 16">
                            <path d="m9.708 6.075-3.024.379-.108.502.595.108c.387.093.464.232.38.619l-.975 4.577c-.255 1.183.14 1.74 1.067 1.74.72 0 1.554-.332 1.933-.789l.116-.549c-.263.232-.65.325-.905.325-.363 0-.494-.255-.402-.704zm.091-2.755a1.32 1.32 0 1 1-2.64 0 1.32 1.32 0 0 1 2.64 0" />
                          </svg></button>
                      </a>
                    </div>
                  </td>
                  <td class="col-1 justify-content-center">
                    <div class="mx-auto" style="width: min-content;">
                      <a href="pembiayaan_user.php?id=${user.id}" onclick="openSpinner()" style="width: 27px">
                        <button type="button" class="p-0 btn btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                          <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                        </svg></button>
                      </a>
                    </div>
                  </td>
                  <td class="col-1 justify-content-center">
                    <div class="mx-auto" style="width: min-content;">
                      <a href="sarpras_user.php?id=${user.id}" onclick="openSpinner()" style="width: 27px">
                        <button type="button" class="p-0 btn btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-boxes" viewBox="0 0 16 16">
                      <path d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434zM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567zM7.5 9.933l-2.75 1.571v3.134l2.75-1.571zm1 3.134 2.75 1.571v-3.134L8.5 9.933zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567zm2.242-2.433V3.504L8.5 5.076V8.21zM7.5 8.21V5.076L4.75 3.504v3.134zM5.258 2.643 8 4.21l2.742-1.567L8 1.076zM15 9.933l-2.75 1.571v3.134L15 13.067zM3.75 14.638v-3.134L1 9.933v3.134z"/>
                    </svg></button>
                      </a>
                    </div>
                  </td>
                  <td class="col-1 justify-content-center">
                    <div class="mx-auto" style="width: min-content;">
                      <a href="kb_user.php?id=${user.id}" onclick="openSpinner()" style="width: 27px">
                        <button type="button" class="p-0 btn btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-plus" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                          <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                          <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>
                        </button>
                      </a>
                    </div>
                  </td>
                  <td class="col-2">
                    <div class="d-flex justify-content-evenly">
                      <a href="detail_user.php?id=${user.id}" onclick="openSpinner()">
                        <button type="button" class="btn btn-outline-primary">
                          <div style="width: 27px; height: 27px">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                              <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                            </svg>
                          </div>
                        </button>
                      </a>
                      <button onclick="showModal(${user.id})" type="button" class="btn btn-outline-primary">
                        <div style="height: 27px">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                          </svg>
                        </div>
                      </button>
                      </div>
                  </td>`;
            userTable.appendChild(row);
          });
        } else {
          const row = document.createElement('tr');
          row.innerHTML = `<td colspan="7" class="text-center">Tidak ada hasil ditemukan</td>`;
          userTable.appendChild(row);
        }
      })
      .catch(error => console.error('Error:', error));
  }, 800)
}