<template>
  <div>
    <input v-model="searchQuery" placeholder="Search employee">
    <button @click="searchEmployee">Search</button>
    <div v-if="employee">
      <p>Name: {{ employee.first_name }} {{ employee.last_name }}</p>
      <p>Position: {{ employee.position }}</p>
      <!-- 更多员工信息 -->
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      searchQuery: '',
      employee: null
    };
  },
  methods: {
    async searchEmployee() {
      try {
        const response = await axios.get(`http://localhost:8000/tourists/${this.searchQuery}`);
        this.employee = response.data;
      } catch (error) {
        console.error("Failed to search employee:", error);
        this.employee = null;
      }
    }
  }
}
</script>