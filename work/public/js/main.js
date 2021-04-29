'use strict';

{
  const token = document.querySelector('main').dataset.token;

  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
      // 非同期通信 fetch
      const url = '?action=toggle';
      const options = {
        method: 'POST',
        body: new URLSearchParams({
          id: checkbox.parentNode.dataset.id,
          token: token,
        }),
      }
      fetch(url, options);
    });
  });

  const deletes = document.querySelectorAll('.delete');
  deletes.forEach(span => {
    span.addEventListener('click', () => {
      if (!confirm('Are you sure?')) {
        return;
      }
      
      // 非同期通信 fetch
      const url = '?action=delete';
      const options = {
        method: 'POST',
        body: new URLSearchParams({
          id: span.parentNode.dataset.id,
          token: token,
        }),
      }
      fetch(url, options);

      // 削除した項目を削除
      span.parentNode.remove();
    });
  });

  const purge = document.querySelector('.purge');
  purge.addEventListener('click', () => {
    if (!confirm('Are you sure?')) {
      return;
    }
    
    // 非同期通信 fetch
    const url = '?action=purge';
    const options = {
      method: 'POST',
      body: new URLSearchParams({
        token: token,
      }),
    }
    fetch(url, options);

    // 削除項目を削除
    const lis = document.querySelectorAll('.todoItems__item');
    lis.forEach(li => {
      if (li.children[0].checked) {
        li.remove();
      }
    })
  });
}
