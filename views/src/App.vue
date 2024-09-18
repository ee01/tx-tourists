<script setup>
import { ref } from 'vue';
import axios from 'axios';
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';

const searchQuery = ref('');
const employee = ref(null);
import templateImage from './assets/form-to-apply-1.jpeg';

const searchEmployee = async () => {
  try {
    const response = await axios.get(`http://localhost:8000/tourists/${searchQuery.value}`);
    employee.value = response.data;
  } catch (error) {
    console.error("Failed to search employee:", error);
    employee.value = null;
  }
};

const downloadAsPDF = async () => {
  const element = document.getElementById('employee-template');
  const canvas = await html2canvas(element);
  const imgData = canvas.toDataURL('image/png');
  const pdf = new jsPDF();
  pdf.addImage(imgData, 'PNG', 0, 0);
  pdf.save('employee-info.pdf');
};

const printEmployeeInfo = () => {
  const element = document.getElementById('employee-template');
  html2canvas(element).then(canvas => {
    const imgData = canvas.toDataURL('image/png');
    const windowContent = `
      <!DOCTYPE html>
      <html>
      <head><title>Print Employee Info</title></head>
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
      <v-container>
        <v-btn color="primary">Primary Button</v-btn>
        <v-text-field v-model="searchQuery" label="输入姓名" />
        <v-btn @click="searchEmployee" color="primary">Search</v-btn>
        <div v-if="employee">
          <div id="employee-template">
            <img :src="templateImage" alt="Template" width="600" />
            <div class="employee-info">
              <p>Name: {{ employee.name }}</p>
              <p>Position: {{ employee.position }}</p>
              <!-- Add more employee details as needed -->
            </div>
          </div>
          <v-btn @click="printEmployeeInfo" color="primary">Print</v-btn>
          <v-btn @click="downloadAsPDF" color="primary">Download</v-btn>
        </div>
      </v-container>
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
</style>
