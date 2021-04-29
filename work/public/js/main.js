'use strict';

{
  const token = document.querySelector('main').dataset.token;
  const input = document.querySelector('[name="title"]');
  const ul = document.querySelector('.todoItems');

  const addTodo = (id, titleValue) => {
    // -- 追加したいDOM --
    // <li class="todoItems__item" data-id="">
    //   <input type="checkbox">
    //   <span></span>
    //   <span class="delete">x</span>
    // </li>

    const li = document.createElement('li');
    li.dataset.id = id;
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    const title = document.createElement('span');
    title.textContent = titleValue;
    const deleteSpan = document.createElement('span');
    deleteSpan.textContent = 'x';
    deleteSpan.classList.add('delete');

    li.appendChild(checkbox);
    li.appendChild(title);
    li.appendChild(deleteSpan);

    ul.insertBefore(li, ul.firstChild);
  }

  input.focus();

  ul.addEventListener('click', e => {
    // toggle
    if (e.target.type === 'checkbox') {
      // 非同期通信 fetch
      const url = '?action=toggle';
      const options = {
        method: 'POST',
        body: new URLSearchParams({
          id: e.target.parentNode.dataset.id,
          token: token,
        }),
      }
      fetch(url, options).then(response => {
        if (!response.ok) {
          throw new Error('This todo has been deleted!');
        }
        return response.json();
      })
      .then(json => {
        if (json.is_done !== e.target.checked) {
          alert('This Todo has been updated. UI is being updated.');
          e.target.checked = json.is_done;
        }
      })
      .catch(err => {
        alert(err.message);
        location.reload();
      });
    }

    // delete
    if (e.target.classList.contains('delete')){
      if (!confirm('Are you sure?')) {
        return;
      }
      
      // 非同期通信 fetch
      const url = '?action=delete';
      const options = {
        method: 'POST',
        body: new URLSearchParams({
          id: e.target.parentNode.dataset.id,
          token: token,
        }),
      }
      fetch(url, options);

      // 削除した項目を削除
      e.target.parentNode.remove();
    }
  })

  document.querySelector('.addItemForm').addEventListener('submit', e => {
    e.preventDefault();

    const title = input.value;

    // 非同期通信 fetch
    const url = '?action=add';
    const options = {
      method: 'POST',
      body: new URLSearchParams({
        title: title,
        token: token,
      }),
    }
    fetch(url, options).then(response => {
      return response.json();
    })
    .then(json => {
      addTodo(json.id, title);
    });

    input.value = '';
    input.focus();
  })

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
