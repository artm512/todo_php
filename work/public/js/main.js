'use strict';

{
  const token = document.querySelector('main').dataset.token;
  const input = document.querySelector('[name="title"]');

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

    const ul = document.querySelector('ul');
    ul.insertBefore(li, ul.firstChild);
  }

  input.focus();

  document.querySelector('form').addEventListener('submit', e => {
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
