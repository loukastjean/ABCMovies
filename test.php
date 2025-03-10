<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css"/>
</head>

<body>
    <div class="flexgroup">
        <div class="image">
            <img src="./images/smoothie-cafe.jpg" alt="AAAAAAAAAAAAAAAAAAAAAAAAAA">
        </div>
        <div class="image">
            <img src="./images/smoothie-cafe.jpg" alt="BBBBBBBBBBBBBBBBBB">
        </div>
    </div>
</body>
</html>

<style>
    .flexgroup {
        display: flex;
    }
</style>




.films-ici-toutv {
  display: block;
  position: relative;
  height: 17px;
  margin: 24px 0 0 45px;
  color: #ffffff;
  font-size: 24px;
  font-weight: 800;
  line-height: 17px;
  z-index: 4;
}

.flex-row-dda {
  position: relative;
  width: 100%;
  height: 300px;
  margin: 0 0 0 43px;
  z-index: 6;
}

.media-group {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  height: 500px;
  align-items: flex-start;
  align-content: flex-start;
  justify-content: space-between;
  margin-top: 5%;
}

.media {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
  flex-wrap: nowrap;
  position: absolute;
  width: 319px;
  height: 262px;
  top: 32px;
  z-index: 6;
}

.picture-related {
  flex-shrink: 0;
  position: relative;
  width: 319px;
  height: 214px;
  background: linear-gradient(180deg, rgba(53, 17, 56, 0.6), #351138);
  z-index: 7;
  overflow: hidden;
  border-radius: 27px;
}

.media-text-background {
  position: absolute;
  width: 319px;
  height: 214px;
  top: 0;
  left: 0;
  background-size: cover;
  z-index: 9;
  background: #351138;
  background: -moz-linear-gradient(top, #35113896 0%, #351138 100%);
  background: -webkit-linear-gradient(top, #35113896 0%, #351138 100%);
  background: linear-gradient(to bottom, #35113896 0%, #351138 100%);
  opacity: 0;
  transform: perspective(1px);
  transition-duration: 1s;
}

.media-text-background:hover {
  opacity: 1;
  transform: perspective(1px);
  transition-duration: 1s;
}

.liked {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: nowrap;
  gap: 10px;
  position: relative;
  width: 44px;
  height: 44px;
  margin: 4px 0 0 270px;
  padding: 3px 3px 3px 3px;
  background: #351138;
  z-index: 10;
  border-radius: 22px;
}

.heart {
  flex-shrink: 0;
  position: relative;
  width: 37px;
  height: 37px;
  background: url("../images/heart.svg") no-repeat center;
  background-size: cover;
  z-index: 11;
  overflow: hidden;
}

.lorem-ipsum-dolor {
  display: flex;
  align-items: flex-end;
  justify-content: flex-start;
  position: relative;
  width: 302px;
  height: 90px;
  margin: 59px 0 0 14px;
  color: #ffffff00;
  font-family: Inter, var(--default-font-family);
  font-size: 18px;
  font-weight: 400;
  line-height: 18.153px;
  text-align: left;
  letter-spacing: 0.6px;
  z-index: 9;
  transform: perspective(1px);
  transition-duration: 1s;
}

.lorem-ipsum-dolor:hover {
  color: #ffffff;
  transform: perspective(1px);
  transition-duration: 1s;
}

.media-picture {
  position: absolute;
  width: 319px;
  height: 214px;
  top: 0;
  left: 0;
  background: url("../images/smoothie-cafe.jpg") no-repeat center;
  background-size: cover;
  z-index: 8;
  border-radius: 27px;
}

.lorem-ipsum {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  flex-shrink: 0;
  position: relative;
  width: 302px;
  height: 48px;
  color: #ffffff;
  font-family: Inter, var(--default-font-family);
  font-size: 24px;
  font-weight: 800;
  line-height: 24px;
  text-align: left;
  letter-spacing: 0.96px;
  z-index: 12;
}

.flex-row-a {
  position: relative;
  width: 831px;
  height: 719px;
  margin: 128px 0 0 404px;
  z-index: 22;
}

.register-background {
  position: absolute;
  width: 45vw;
  height: 75vh;
  top: 0;
  left: 0;
  background: rgba(15, 15, 15, 0.6);
  z-index: 2;
  border-radius: 32px;
  backdrop-filter: blur(50px);
}

.flex-column {
  position: absolute;
  width: 449px;
  height: 603.99px;
  top: 43.01px;
  left: 88px;
  font-size: 0px;
  z-index: 25;
}

.create-account-heading, .login-account-heading {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  width: 382px;
  height: 54.133px;
  margin: 0 0 0 37px;
  color: #9544b0;
  font-family: Inter, var(--default-font-family);
  font-size: 32px;
  font-weight: 800;
  line-height: 38.727px;
  text-align: center;
  white-space: nowrap;
  z-index: 25;
}

.email-bar, .username-bar, .username-email-bar, .password-bar {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: nowrap;
  position: relative;
  margin: 1% 0 0 1%;
  padding: 0 19px 0 19px;
  background: rgba(36, 36, 36, 0.42);
  border: 3px solid #9544b0;
  z-index: 13;
  border-radius: 1000px;
  box-sizing: content-box;
}

.username-input, .email-input, .username-email-input, .password-input {
  display: block;
  position: relative;
  margin: 5% 0 0 5%;
  color: #9544b0;
  background-color: rgba(0, 0, 0, 0);
  border: none;
  font-family: Inter, var(--default-font-family);
  font-size: 24px;
  font-weight: 800;
  text-align: left;
  white-space: nowrap;
  z-index: 12;
}

.enregistrer, .connexion {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: nowrap;
  gap: 10px;
  position: relative;
  width: 449px;
  height: 83px;
  margin: 57.479px 0 0 0;
  padding: 19px 119px 19px 119px;
  cursor: pointer;
  background: #9544b0;
  border: none;
  z-index: 3;
  border-radius: 70px;
}

.enregistrer-text, .connexion-text {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  flex-basis: auto;
  position: relative;
  width: 178px;
  height: 39px;
  color: #ffffff;
  font-family: Inter, var(--default-font-family);
  font-size: 32px;
  font-weight: 800;
  line-height: 39px;
  text-align: center;
  white-space: nowrap;
  z-index: 4;
}

.flex-column-a {
  position: absolute;
  width: 41px;
  height: 308px;
  top: 195px;
  left: 537px;
  z-index: 24;
}

.info-icon-username .info-icon-password {
  position: relative;
  width: 41px;
  height: 41px;
  margin: 0 0 0 0;
  background: url(../images/info-icon.svg)
      no-repeat center;
  background-size: cover;
  z-index: 24;
  overflow: hidden;
}

.info-username-group {
  display: flex;
  align-items: center;
  flex-wrap: nowrap;
  gap: 10px;
  position: absolute;
  width: 253px;
  height: 50px;
  top: 231px;
  left: 578px;
  padding: 15px 23px 15px 23px;
  cursor: pointer;
  background: rgba(15, 15, 15, 0.8);
  border: none;
  z-index: 22;
  border-radius: 20px;
}

.minimum-maximum-caracteres {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  flex-shrink: 0;
  position: relative;
  width: 209px;
  height: 38px;
  color: #ffffff;
  font-family: Inter, var(--default-font-family);
  font-size: 16px;
  font-weight: 500;
  line-height: 38px;
  text-align: left;
  z-index: 23;
}

.info-password-group {
  display: flex;
  align-items: center;
  flex-wrap: nowrap;
  gap: 10px;
  position: absolute;
  width: 253px;
  height: 114px;
  top: 498px;
  left: 578px;
  padding: 14px 18px 14px 18px;
  background: rgba(15, 15, 15, 0.8);
  z-index: 19;
  border-radius: 20px;
}

.minimum-caracteres-letter-number-special {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  flex-shrink: 0;
  position: relative;
  width: 194px;
  height: 95px;
  color: #ffffff;
  font-family: Inter, var(--default-font-family);
  font-size: 16px;
  font-weight: 500;
  line-height: 19.364px;
  text-align: left;
  z-index: 20;
}
  