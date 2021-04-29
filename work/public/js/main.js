'use strict';

{
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
      // 非同期通信 fetch
      const url = '?action=toggle';
      const options = {
        method: 'POST',
        body: new URLSearchParams({
          id: checkbox.dataset.id,
          token: checkbox.dataset.token,
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
          id: span.dataset.id,
          token: span.dataset.token,
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
    purge.parentNode.submit();
  });
}
