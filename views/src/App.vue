<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';
import md5 from 'md5';
import dayjs from 'dayjs';

const form = ref(null);
const searchQuery = ref('');
const password = ref('');
const tourist = ref(null);
const formError = ref(''); // 新增状态
const passwordRules = [v => md5(v) == 'd964173dc44da83eeafa3aebbee9a1a0' || '密码错误'];
import templateImage from './assets/form-to-apply-1.jpeg';

const touristBirthday = computed(() => {
  const birthday = tourist.value?.raw.answerContents.find(a => a.title == '请上传身份证照片')?.value[3] || '出生日期';
  if (birthday && birthday !== '出生日期') {
    return dayjs(birthday).format('DD/MM/YYYY');
  }
  return birthday;
});
const isFemale = computed(() => {
  const is = tourist.value?.raw.answerContents.find(a => a.title == '请上传身份证照片')?.value[2][0] == '女';
  return is;
});
const marry = computed(() => tourist.value?.raw.answerContents.find(a => a.title == '婚姻状况')?.value[0] || '已婚');
const passportType = computed(() => tourist.value?.raw.answerContents.find(a => a.title == '请上传护照照片')?.value[1][0] || 'P');

const searchTourist = async () => {
  const { valid } = await form.value.validate()
  if (!valid) return
  try {
    const response = await axios.get(`http://localhost:8000/tourists?name=${searchQuery.value}`);
    if (!response.data.length) {
      formError.value = '未找到相关信息';
      return
    }
    formError.value = '';
    tourist.value = response.data[0];
  } catch (error) {
    console.error("Failed to search tourist:", error);
    tourist.value = null;
  }
};

const downloadAsPDF = async () => {
  const element = document.getElementById('tourist-template');
  const canvas = await html2canvas(element, { scale: 2 }); // 提高分辨率
  const imgData = canvas.toDataURL('image/png');
  const pdf = new jsPDF({
    orientation: 'portrait',
    unit: 'mm',
    format: 'a4'
  });
  const imgProps = pdf.getImageProperties(imgData);
  const pdfWidth = pdf.internal.pageSize.getWidth();
  const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
  pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
  pdf.save('tourist-info.pdf');
};

const printTouristInfo = () => {
  const element = document.getElementById('tourist-template');
  html2canvas(element).then(canvas => {
    const imgData = canvas.toDataURL('image/png');
    const windowContent = `
      <!DOCTYPE html>
      <html>
      <head><title>Print Tourist Info</title></head>
      <body>
      <img src="${imgData}" />
      </body>
      </html>`;
    const printWindow = window.open('', '', 'width=800,height=600');
    if (printWindow) {
      printWindow.document.open();
      printWindow.document.write(windowContent);
      printWindow.document.close();
      printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
      };
    } else {
      console.error("Failed to open print window");
    }
  });
};
</script>

<template>
  <v-app>
    <v-main>
      <v-container class="d-flex flex-column align-center justify-center fill-height">
        <v-form ref="form">
          <v-card class="pa-5" min-width="800">
            <v-img src="@/assets/login-banner.jpg" height="200" class="mb-4" v-if="!tourist"></v-img>
            <v-card-title class="text-h5 text-center">日本出入境信息核查系统</v-card-title>
            <v-card-text>
              <v-text-field v-model="searchQuery" label="输入完整姓名" outlined required :rules="[v => !!v || '不能为空']"></v-text-field>
              <v-text-field v-model="password" label="输入查询密码" type="password" outlined validate-on="submit" :rules="passwordRules"></v-text-field>
              <v-alert v-if="formError" type="error" class="mt-2">{{ formError }}</v-alert>
            </v-card-text>
            <v-card-actions>
              <v-btn @click="searchTourist" color="primary" block large>查询打印申请表</v-btn>
            </v-card-actions>
          </v-card>
        </v-form>
        <v-card v-if="tourist" class="mt-5 pa-5" min-width="800">
          <v-card-title class="text-h5">查询结果</v-card-title>
          <v-card-actions class="justify-end">
            <v-btn @click="printTouristInfo" color="primary" class="ml-2">Print</v-btn>
            <v-btn @click="downloadAsPDF" color="primary" class="ml-2">Download</v-btn>
          </v-card-actions>
          <v-card-text>
            <div id="tourist-template">
              <img :src="templateImage" alt="Template" width="800" />
              <div class="tourist-info">
                <p style="left: 640px; top: 290px;">{{ tourist.first_name }}</p>
                <p style="left: 640px; top: 315px;">{{ tourist.last_name }}</p>
                <p style="left: 320px; top: 290px ;">{{ tourist.raw.answerContents.find(a => a.title == '英文姓氏')?.value }}</p>
                <p style="left: 320px; top: 315px ;">{{ tourist.raw.answerContents.find(a => a.title == '英文名字')?.value }}</p>
                <p style="left: 320px; top: 340px ;">{{ tourist.raw.answerContents.find(a => a.title == '曾用名')?.value || '无' }}</p>
                <p style="left: 640px; top: 340px ;">{{ tourist.raw.answerContents.find(a => a.title == '英文曾用名')?.value || '无' }}</p>
                <p style="left: 160px; top: 375px ;">{{ touristBirthday }}</p>
                <p style="left: 360px; top: 375px ;">{{ tourist.raw.answerContents.find(a => a.title == '出生地点')?.value || '无' }}</p>
                <p style="left: 160px; top: 404px ;">{{ !isFemale ? 'X' : '' }}</p>
                <p style="left: 222px; top: 404px ;">{{ isFemale ? 'X' : '' }}</p>
                <p style="left: 447px; top: 404px ;">{{ marry == '单身' ? 'X' : '' }}</p>
                <p style="left: 529px; top: 404px ;">{{ marry == '已婚' ? 'X' : '' }}</p>
                <p style="left: 610px; top: 404px ;">{{ marry == '离婚' ? 'X' : '' }}</p>
                <p style="left: 692px; top: 404px ;">{{ marry == '丧偶' ? 'X' : '' }}</p>
                <p style="left: 240px; top: 430px ;">{{ tourist.raw.answerContents.find(a => a.title == '国籍')?.value[0] || '无' }}</p>
                <p style="left: 380px; top: 455px ;">{{ tourist.raw.answerContents.find(a => a.title == '曾有或另有国籍（如有）')?.value[0] || '无' }}</p>
                <p style="left: 200px; top: 480px ;">{{ tourist.raw.answerContents.find(a => a.title == '请上传身份证照片')?.value[5] || '身份证' }}</p>
                <p style="left: 202px; top: 511px ;">{{ passportType == 'W' ? 'X' : '' }}</p>
                <p style="left: 275px; top: 511px ;">{{ passportType == 'G' ? 'X' : '' }}</p>
                <p style="left: 348px; top: 511px ;">{{ passportType == 'P' ? 'X' : '' }}</p>
                <p style="left: 419px; top: 511px ;">{{ passportType == 'O' ? 'X' : '' }}</p>
                <p style="left: 550px; top: 511px ;">{{ tourist.raw.answerContents.find(a => a.title == '请上传护照照片')?.value[2] || '护照号' }}</p>
                <!-- Add more tourist details as needed -->
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-container>
      <div class="watermark">
        <div v-for="n in 30" :key="n" class="watermark-text">日本出入境中心</div>
      </div>
    </v-main>
  </v-app>
</template>

<style scoped lang="less">
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}

#tourist-template {
  position: relative;

  .tourist-info {
    /* Add your styles here */
    position: absolute;
    top: 0;
    left: 0;
    p {
      position: absolute;
      width: 200px;
    }
  }
}

.watermark {
  position: fixed;
  top: 0;
  left: 0;
  width: 150%;
  height: 200%;
  margin: -30% 0 0 -20%;
  pointer-events: none;
  z-index: 9999;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  opacity: 0.05;
  font-size: 2rem;
  color: #000;
  transform: rotate(-45deg);
  white-space: nowrap;
}

.watermark-text {
  flex: 1 0 20%;
  text-align: center;
}
</style>
