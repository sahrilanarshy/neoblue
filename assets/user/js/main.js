(() => {
  document.addEventListener('DOMContentLoaded', () => {
    const showListBtn = document.getElementById('showListBtn');
    const showCardBtn = document.getElementById('showCardBtn');
    const listView = document.getElementById('listView');
    const cardView = document.getElementById('cardView');

    if (showListBtn && showCardBtn && listView && cardView) {
      showListBtn.addEventListener('click', () => {
        listView.style.display = 'block';
        cardView.style.display = 'none';
        showListBtn.classList.add('active');
        showCardBtn.classList.remove('active');
      });

      showCardBtn.addEventListener('click', () => {
        cardView.style.display = 'block';
        listView.style.display = 'none';
        showCardBtn.classList.add('active');
        showListBtn.classList.remove('active');
      });
    }
  });
})();
