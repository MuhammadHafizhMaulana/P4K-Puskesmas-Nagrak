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

function showModal(userId, userName) {
  userIdToDelete = userId;
  document.getElementsByClassName('modal-body')[0].innerHTML = `Apakah anda yakin akan menghapus data kesehatan user dari <span style="font-weight: bold;">${userName}</span>?`
  var myModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'), {
    keyboard: false
  });
  myModal.show();
}

function confirmDelete() {
  if (userIdToDelete !== null) {
    openSpinner();
    window.location.href = 'proses/hapus_kesehatan_user.php?id=' + userIdToDelete;
  }
}

function ucwords(str) {
  return str.replace(/\b\w/g, function(char) {
      return char.toUpperCase();
  });
}

searchType.addEventListener('input', () => {
  console.log(searchType.value)
  // Temukan elemen form
  const form = document.getElementById('searchForm');

  // Temukan elemen select yang akan diganti
  const select = document.getElementById('searchValue');

  // Jika elemen select ditemukan, hapus elemen select
  if (select) {
    select.remove();
  }

  var element;
  if (searchType.value == 'nama') {

    // Buat elemen element baru
    element = document.createElement('input');
    element.id = 'searchValue';
    element.name = 'searchValue';
    element.placeholder = 'Masukan nama user';
    element.type = 'text';
    element.className = 'form-control';
    element.setAttribute('aria-label', 'Text input with dropdown button');
  } else if (searchType.value == 'goldar') {

    // Buat elemen select baru dengan opsi yang diinginkan
    element = document.createElement('select');
    element.id = 'searchValue';
    element.name = 'searchValue';
    element.className = 'form-select bg-primary text-white icon-white';
    element.setAttribute('aria-label', 'Default select example');
    element.setAttribute('oninput', 'getUserData()');

    // Tambahkan opsi ke elemen select
    const options = [{
        value: '',
        text: 'Pilih goldar yang dicari'
      },
      {
        value: '-',
        text: 'Belum diketahui'
      },
      {
        value: 'a+',
        text: 'A+'
      },
      {
        value: 'o+',
        text: 'O+'
      },
      {
        value: 'b+',
        text: 'B+'
      },
      {
        value: 'ab+',
        text: 'AB+'
      },
      {
        value: 'a-',
        text: 'A-'
      },
      {
        value: 'o-',
        text: 'O-'
      },
      {
        value: 'b-',
        text: 'B-'
      },
      {
        value: 'ab-',
        text: 'AB-'
      }
    ];

    options.forEach(optionData => {
      const option = document.createElement('option');
      option.value = optionData.value;
      option.textContent = optionData.text;
      element.appendChild(option);
    });


  }
  // Temukan parent dari elemen input (div dengan class "input-group mb-3")
  const parent = form.querySelector('.input-group.mb-3');

  // Tambahkan elemen select baru ke parent
  if (parent) {
    parent.appendChild(element);
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
    console.log(searchValue);
    console.log(searchType)
    const userTable = document.getElementById('userTable');

    spinner.setAttribute('style', 'z-index: 2')

    fetch('proses/searchListKesehatanUser.php', {
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
        userTable.innerHTML = '';
        spinner.setAttribute('style', 'z-index: 0;')
        if (data.length > 0) {
          data.forEach((value, index) => {
            console.log('cek')
            const row = document.createElement('tr');
            row.innerHTML = `<th claass="col-1 text-center" scope="row">${index+1}</th>
                    <td class="col-2">${value.nama}</td>
                    <td class="col-2 text-center">${ucwords(value.status)}</td>
                    <td class="col-2 text-center">${value.goldar.toUpperCase()}</td>
                    <td class="col-3 text-center">${value.hpht}</td>
                    <td class="col-2">
                      <div class="d-flex justify-content-evenly">
                        <a href="kesehatan_user.php?id=${value.id_user}" onclick="openSpinner()" style="width: 27px">
                          <button type="button" class="p-0 btn btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-lg" viewBox="0 0 16 16">
                              <path d="m9.708 6.075-3.024.379-.108.502.595.108c.387.093.464.232.38.619l-.975 4.577c-.255 1.183.14 1.74 1.067 1.74.72 0 1.554-.332 1.933-.789l.116-.549c-.263.232-.65.325-.905.325-.363 0-.494-.255-.402-.704zm.091-2.755a1.32 1.32 0 1 1-2.64 0 1.32 1.32 0 0 1 2.64 0" />
                            </svg></button>
                        </a>
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