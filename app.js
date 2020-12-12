function validate() {
    var form = event.target
    var aElements = form.querySelectorAll('[data-type]')
    for (var i = 0; i < aElements.length; i++) {
        aElements[i].classList.remove('error')
        var sDataType = aElements[i].getAttribute('data-type')
        switch (sDataType) {
            case 'string':
                isStringValid(aElements[i])
                break
            case 'email':
                isEmailValid(aElements[i])
                break
        }
    }
}

function isStringValid(oElement) {
    var iMin = oElement.getAttribute('data-min')
    var iMax = oElement.getAttribute('data-max')
    if (oElement.value.length < iMin) {
        oElement.classList.add('error')
    }
    if (oElement.value.length > iMax) {
        oElement.classList.add('error')
    }
}

function isEmailValid(oElement) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test(String(oElement.value).toLowerCase())) {
        oElement.classList.add('error')
    }
}

function showModal() {
    document.querySelector('#signup-modal').style.display = 'block'

}

function closeModal() {
    document.querySelector('#signup-modal').style.display = 'none'
}

async function signUp() {
    let form = event.target
    if (form.querySelector('.error')) {
        console.log('form has an error')
        return
    }

    let confirmPasswordInput = form.querySelector('#signup-confirm-password')
    if (form.querySelector('#signup-password').value != confirmPasswordInput.value) {
        confirmPasswordInput.classList.add('error')
        return
    }

    let connection = await fetch('api/api-signup.php', {
        method: 'POST',
        body: new FormData(form)
    })

    if (!connection.ok) {
        let jsonResponse = await connection.json()
        if (jsonResponse.message == 'Email already exists') {
            form.querySelector('#signup-email').classList.add('error')
            console.log('email already exists')
            return
        }
    }

    location.href = 'index.php'

}

async function login() {
    let form = event.target
    form.firstElementChild.childNodes[3].classList.remove('error')
    form.childNodes[3].childNodes[3].classList.remove('error')

    if (form.querySelector('.error')) {
        console.log('form has an error')
        return
    }

    let connection = await fetch('api/api-login.php', {
        method: 'POST',
        body: new FormData(form)
    })

    if (!connection.ok) {
        form.firstElementChild.childNodes[3].classList.add('error')
        form.childNodes[3].childNodes[3].classList.add('error')
        return
    }

    location.href = 'index.php'
}

function enableTweetBtn() {
    let tweetBtn = document.querySelector('#tweet-btn')
    tweetBtn.disabled = false
    tweetBtn.classList.remove('tweet-btn-disabled')
    tweetBtn.classList.add('tweet-btn-enabled')
}

async function tweet() {
    let connection = await fetch('api/api-tweet.php', {
        method: 'POST',
        body: new FormData(event.target)
    })

    if (!connection.ok) {
        console.log('not 200')
        return
    }

    let jsonResponse = await connection.json()
    let tweetDiv = `
            <div id="tweet-${jsonResponse.tweetId}" class="tweet">
                <a href="#">
                    <img class="tweet-img" src="images/${jsonResponse.userImage}" alt="Profile Image of ${jsonResponse.name}">
                </a>
                <div>
                    <div>
                        <div id="user-${jsonResponse.userId}" class="tweet-main-info">
                            <p>${jsonResponse.name}</p>
                            <p>@${jsonResponse.username}</p>
                            <p>· 1 second ago</p>
                            <i onclick="editDeletePopup(${jsonResponse.tweetId})" class="fas fa-ellipsis-h"></i>
                        </div>
                        <p>${jsonResponse.tweetBody}</p>
                    </div>
                    <div>
                        <div>
                            <i class="far fa-comment"></i>
                            <p>0</p>
                        </div>
                        <div>
                            <i class="fas fa-retweet"></i>
                            <p>0</p>
                        </div>
                        <div>
                            <i onclick="likeTweet(${jsonResponse.tweetId})" class="far fa-heart"></i>
                            <p>0</p>
                        </div>
                        <i class="far fa-share-square"></i>
                        <div class="edit-delete-tweet">
                            <button onclick="getEditTweet(${jsonResponse.tweetId})">
                                <i class="fas fa-pen"></i>
                                <p>Edit</p>
                            </button>
                            <button onclick="deleteTweet(${jsonResponse.tweetId})">
                                <i class="far fa-trash-alt"></i>
                                <p>Delete</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>`

    document.querySelector('#tweets').insertAdjacentHTML('afterbegin', tweetDiv)
}

async function editDeletePopup(userId) {
    let editDeletePopup = document.querySelector(`#user-${userId}`).parentNode.nextElementSibling.childNodes[9]

    if (editDeletePopup.style.display == 'flex') {
        editDeletePopup.style.setProperty("display", "none", "important")
        return
    }

    let connection = await fetch('api/api-get-session-id.php')

    if (!connection.ok) {
        console.log('not 200')
        return
    }

    let jsonResponse = await connection.json()

    if (jsonResponse.userId == userId) {
        editDeletePopup.style.setProperty("display", "flex", "important")
    }
}

async function deleteTweet(deleteTweetId) {
    let form = new FormData()
    form.append('deleteTweetId', deleteTweetId)

    let connection = await fetch('api/api-delete-tweet.php', {
        method: 'POST',
        body: form
    })

    if (!connection.ok) {
        console.log('not 200')
        return
    }

    let jsonResponse = await connection.json()

    if (jsonResponse.tweetId == deleteTweetId) {
        document.querySelector(`#tweet-${deleteTweetId}`).remove()
    }

}

function getEditTweet(editTweetId) {
    let tweetBody = document.querySelector(`#tweet-${editTweetId}`).childNodes[3].childNodes[1].childNodes[3]
    let userId = document.querySelector(`#user-${editTweetId}`).id
    editDeletePopup(userId)

    let editTweetForm = `
    <form class="edit-tweet-form" onsubmit="return false">
        <input type="text" name="editTweetBody" value="${tweetBody.innerHTML}">
        <button onclick="editTweet(${editTweetId})"><i class="fas fa-check"></i></button>
        <button onclick=""><i class="fas fa-times"></i></button>
    </form>`

    if (tweetBody.style.display == 'none') { return }

    tweetBody.insertAdjacentHTML('afterend', editTweetForm)
    tweetBody.style.display = 'none'


}

async function editTweet(editTweetId) {
    let tweetBody = document.getElementById(`tweet-${editTweetId}`).childNodes[3].childNodes[1].childNodes[3]
    let form = new FormData(document.querySelector('.edit-tweet-form'))
    form.append('editTweetId', editTweetId)

    let connection = await fetch('api/api-edit-tweet.php', {
        method: 'POST',
        body: form
    })

    form = document.querySelector('.edit-tweet-form')

    if (!connection.ok) {
        form.childNodes[1].classList.add('error')
        return
    }

    if (!form.childNodes[1].value) {
        form.childNodes[1].classList.add('error')
        return
    }

    let jsonResponse = await connection.json()

    if (jsonResponse.tweetId == editTweetId) {
        tweetBody.style.display = 'block'
        tweetBody.innerHTML = jsonResponse.tweetBody
        form.remove()
    }
}

function showSearchResults() {
    document.querySelector('#search-results').style.display = 'block'
}
function hideSearchResults() {
    document.querySelector('#search-results').style.display = 'none'
}

async function startSearch() {
    let inputValue = event.target.value
    if (!inputValue) {
        console.log('empty search input')
        return
    }

    let searchResults = document.querySelector('#search-results')
    searchResults.innerHTML = ''

    let connection = await fetch(`api/api-search-by-username.php?username=${inputValue}`)

    if (!connection.ok) {
        console.log('not 200')
        return
    }
    let jsonResponse = await connection.json()

    jsonResponse.forEach(jsonItem => {
        let searchResultDiv = `
        <div>
            <img src="images/${jsonItem.sUserImage}" alt="Profile Image of ${jsonItem.sName}">
            <div>
                <p>${jsonItem.sName}</p>
                <p>@${jsonItem.sUsername}</p>
            </div>
        </div>
    `
        searchResults.insertAdjacentHTML('afterbegin', searchResultDiv)
    })

}

async function follow(userId) {
    let followButton = event.target
    let form = new FormData()
    form.append('userId', userId)
    let connection = await fetch('api/api-follow.php', {
        method: 'POST',
        body: form
    })

    if (!connection.ok) {
        followButton.classList.add('error')
        followButton.innerHTML = 'Already following'
        followButton.style.textAlign = 'center'
        console.log('not 200')
        return
    }

    followButton.parentNode.parentNode.remove()
}

async function likeTweet(tweetId) {
    let likeButton = event.target
    let numberOfTweets = likeButton.nextElementSibling
    if (likeButton.style.color == 'rgb(240, 128, 128)') return
    let form = new FormData()
    form.append('tweetId', tweetId)

    let connection = await fetch('api/api-like-tweet.php', {
        method: 'POST',
        body: form
    })

    let jsonResponse = await connection.json()

    if (tweetId == jsonResponse.tweetIdToLike) {
        likeButton.style.setProperty('color', 'rgb(240, 128, 128)', 'important')
        numberOfTweets.innerHTML++
    }
}

let navButtons = document.querySelector('#left').children
let subpages = document.querySelectorAll('.mid')

// for (let i = 0; i < navButtons.length; i++) {
//     if (navButtons[i].classList == 'active') {
//         let subpageId = navButtons[i].getAttribute('data-id')
//         document.querySelector(`#${subpageId}`).style.setProperty('display', 'block', 'important')
//     } else {
//         document.querySelector('[data-id="home"]').classList.add('active')
//     }
// }

for (const item of navButtons) {
    item.addEventListener('click', () => {
        subpages.forEach((item) =>
            item.style.display = 'none'
        )
        document.querySelector('#home').style.setProperty('display', 'none', 'important')
        let subpageId = event.target.getAttribute('data-id')
        let subpage = document.querySelector(`#${subpageId}`)
        subpage.style.setProperty('display', 'block', 'important')
        for (const item of navButtons) {
            item.classList.remove('active')
        }
        event.target.classList.add('active')
    })
}

async function getMessagesPeople() {
    // FIRST CLEAR DIV
    let connection = await fetch('api/api-get-messages-people.php')

    if (!connection.ok) {
        let errorDiv = `
        <div>
            <article>
                <div>
                    <p>No users to display.</p>
                </div>
            </article>
        </div>`
        document.querySelector('#messages-people').insertAdjacentHTML('afterbegin', errorDiv)
        return
    }

    let jsonResponse = await connection.json()
    // console.log(stringResponse)
    // let jsonResponse = JSON.parse(stringResponse)
    for (let i = 0; i < jsonResponse.length; i++) {
        console.log(jsonResponse[i])
    }
}