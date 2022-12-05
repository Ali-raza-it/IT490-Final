<!-- code referenced from https://github.com/aj-4/5m-forum/blob/main/index.html---> 

<?php

session_start()


?>
<body>
  <script id=replace_with_navbar src=nav.js></script>
</div> 
  <div class="top-bar">
      <h1>
          Movie Discussion Forum
      </h1>
  </div>
  <div id="wrapper">
    <div id="menu">
        
        <a class="item" href="/forum/create_topic.php">Create a topic</a> 
      
      <ol>
      </ol>
  </div>
  <script src="discussion.js"></script>

  <script>
      function addComment(comment) {
            var commentHtml = `
                <div class="comment">
                    <div class="top-comment">
                        <p class="user">
                            $
                        </p>
                        <p class="comment-ts">
                            ${new Date(comment.date).toLocaleString()}
                        </p>
                    </div>
                    <div class="comment-content">
                        ${comment.content}
                    </div>
                </div>
            `
            comments.insertAdjacentHTML('beforeend', commentHtml);
        }
      console.log(threads);
      var container = document.querySelector('ol');
      for (let thread of threads) {
          var html = `
          <li class="row">
              <a href="thread.html?${thread.id}">
                  <h4 class="title">
                      ${thread.title}
                  </h4>
                  <div class="bottom">
                      <p class="timestamp">
                          ${new Date(thread.date).toLocaleString()}
                      </p>
                      <p class="comment-count">
                          ${thread.comments.length} comments
                      </p>
                  </div>
              </a>
          </li>
          `
          container.insertAdjacentHTML('beforeend', html);
      }
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <style>
      body {
          margin: 10px 60px;
      }
      a {
          text-decoration: none;
          color: black;
      }
      h1, h4, ol {
          margin: 0;
      }
      p {
          margin: 5px 0;
      }
      .top-bar {
          background-color: skyblue;
          padding: 0 40px;
      }
      .main {
          background-color: #F6F6EF;
          padding: 10px 15px;
      }
      .row {
          padding: 5px 0;
      }
      .bottom {
          display: flex;
          color: grey;
          font-size: 12px;
      }
      .timestamp {
          padding-right: 10px;
      }
  </style>
</body>
