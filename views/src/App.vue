<script setup>
import { ref } from 'vue';
import axios from 'axios';
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';
import md5 from 'md5';

const form = ref(null);
const searchQuery = ref('');
const password = ref('');
const tourist = ref(null);
const formError = ref(''); // 新增状态
const passwordRules = [v => md5(v) == 'd964173dc44da83eeafa3aebbee9a1a0' || '密码错误'];
import templateImage from './assets/form-to-apply-1.jpeg';

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
    tourist.value = response.data;
  } catch (error) {
    console.error("Failed to search tourist:", error);
    tourist.value = null;
  }
};

const downloadAsPDF = async () => {
  const element = document.getElementById('tourist-template');
  const canvas = await html2canvas(element);
  const imgData = canvas.toDataURL('image/png');
  const pdf = new jsPDF();
  pdf.addImage(imgData, 'PNG', 0, 0);
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
              <img :src="templateImage" alt="Template" width="600" />
              <div class="tourist-info">
                <p>Name: {{ tourist.name }}</p>
                <p>Position: {{ tourist.position }}</p>
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

<style scoped>
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
