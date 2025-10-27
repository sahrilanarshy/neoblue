    //index
    
    // Ambil elemen-elemen yang dibutuhkan
    const showListBtn = document.getElementById('showListBtn');
    const showCardBtn = document.getElementById('showCardBtn');
    const listView = document.getElementById('listView');
    const cardView = document.getElementById('cardView');

    // Event listener untuk tombol List View
    showListBtn.addEventListener('click', () => {
    // Tampilkan List View dan sembunyikan Card View
    listView.style.display = 'block';
    cardView.style.display = 'none';

    // Atur status 'active' pada tombol
    showListBtn.classList.add('active');
    showCardBtn.classList.remove('active');
    });

    // Event listener untuk tombol Card View
    showCardBtn.addEventListener('click', () => {
    // Tampilkan Card View dan sembunyikan List View
    cardView.style.display = 'block';
    listView.style.display = 'none';

    // Atur status 'active' pada tombol
    showCardBtn.classList.add('active');
    showListBtn.classList.remove('active');
    });
