<template>
    <div style="padding: 10px;">
      <form @submit.prevent="handleSubmit">
        <!-- Insurer Code -->
        <div class="form-group">
          <label for="insurer_code">Insurer Code</label>
          <input 
            id="insurer_code" 
            v-model="form.insurer_code" 
            type="text" 
            :class="{'error-border': errors.insurer_code}" 
          />
          <span v-if="errors.insurer_code" class="error">{{ errors.insurer_code }}</span>
        </div>
  
        <!-- Provider Name -->
        <div class="form-group">
          <label for="provider_name">Provider Name</label>
          <input 
            id="provider_name" 
            v-model="form.provider_name" 
            type="text" 
            :class="{'error-border': errors.provider_name}" 
          />
          <span v-if="errors.provider_name" class="error">{{ errors.provider_name }}</span>
        </div>
  
        <!-- Encounter Date -->
        <div class="form-group">
          <label for="encounter_date">Encounter Date</label>
          <input 
            id="encounter_date" 
            v-model="form.encounter_date" 
            type="date" 
            :class="{'error-border': errors.encounter_date}" 
          />
          <span v-if="errors.encounter_date" class="error">{{ errors.encounter_date }}</span>
        </div>
  
        <!-- Specialty -->
        <div class="form-group">
          <label for="specialty">Specialty</label>
          <select 
            id="specialty" 
            v-model="form.specialty_id" 
            :class="{'error-border': errors.specialty}" 
          >
            <option v-for="specialty in specialties" :key="specialty.id" :value="specialty.id">
              {{ specialty.name }}
            </option>
          </select>
          <span v-if="errors.specialty" class="error">{{ errors.specialty }}</span>
        </div>
  
        <!-- Priority Level -->
        <div class="form-group">
          <label for="priority_level">Priority Level</label>
          <input 
            id="priority_level" 
            v-model="form.priority_level" 
            type="text" 
            :class="{'error-border': errors.priority_level}" 
          />
          <span v-if="errors.priority_level" class="error">{{ errors.priority_level }}</span>
        </div>
  
        <!-- Multi-Item Input Area -->
        <div class="form-group">
          <div class="item-row">
            <p style="width: 15%">Item</p>
            <p style="width: 15%">Unit Price</p>
            <p style="width: 15%">Qty</p>
            <p style="width: 15%">Sub Total</p>
          </div>
          <div v-for="(item, index) in form.claim_items" :key="index" class="item-row">
            <input 
              v-model="item.name" 
              type="text" 
              placeholder="Item" 
              :class="{'error-border': errors[`claim_items.${index}.name`]}" 
            />
            <input 
              v-model.number="item.unit_price" 
              type="number" 
              placeholder="Unit Price" 
              @input="updateSubtotal(item)"
              :class="{'error-border': errors[`claim_items.${index}.unit_price`]}" 
            />
            <input 
              v-model.number="item.quantity" 
              type="number" 
              placeholder="Quantity" 
              @input="updateSubtotal(item)"
              :class="{'error-border': errors[`claim_items.${index}.quantity`]}" 
            />
            <input 
              v-model.number="item.subtotal" 
              type="number" 
              placeholder="Subtotal" 
              disabled 
            />
            <button type="button" class="btn btn-remove" @click="removeItem(index)">Remove</button>
            <div v-if="errors[`claim_items.${index}`]" class="error">
              {{ errors[`claim_items.${index}`] }}
            </div>
          </div>
          <div class="total-row">
            <div style="width: 50%;">
                <button type="button" class="btn btn-add" @click="addItem">Add Item</button>
            </div>
            <div class="total">
              <label>Total:</label>
              <input type="number" :value="calculateTotal()" disabled />
            </div>
          </div>
        </div>
  
        <!-- Submit Button -->
        <div class="form-group">
          <button type="submit" class="btn btn-submit">Submit</button>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { reactive, ref } from 'vue';

  import axios from 'axios';
  
  const specialties = [
    { id: 1, name: 'Cardiology' },
    { id: 2, name: 'Orthopedics' },
    { id: 3, name: 'Neurology' },
    { id: 4, name: 'Pediatrics' },
  ];
  
  const form = reactive({
    insurer_code: '',
    provider_name: '',
    encounter_date: '',
    specialty_id: '',
    priority_level: '',
    claim_items: [
      { name: '', unit_price: 0, quantity: 0, subtotal: 0 },
    ],
  });
  
  const errors = ref({});
  
  const addItem = () => {
    form.claim_items.push({ name: '', unit_price: 0, quantity: 0, subtotal: 0 });
  };
  
  const removeItem = (index) => {
    form.claim_items.splice(index, 1);
  };
  
  const updateSubtotal = (item) => {
    item.subtotal = item.unit_price * item.quantity;
  };
  
  const calculateTotal = () => {
    return form.claim_items.reduce((total, item) => total + item.subtotal, 0);
  };
  
  const validate = () => {
    errors.value = {};
  
    if (!form.insurer_code) {
      errors.value.insurer_code = 'Insurer code is required';
    }
    if (!form.provider_name) {
      errors.value.provider_name = 'Provider name is required';
    }
    if (!form.encounter_date) {
      errors.value.encounter_date = 'Encounter date is required';
    }
    if (!form.specialty) {
      errors.value.specialty = 'Specialty is required';
    }
    if (!form.priority_level) {
      errors.value.priority_level = 'Priority level is required';
    }
    form.claim_items.forEach((item, index) => {
      if (!item.name) {
        errors.value[`claim_items.${index}.name`] = 'name is required';
      }
      if (!item.unit_price) {
        errors.value[`claim_items.${index}.unit_price`] = 'Unit price is required';
      }
      if (!item.quantity) {
        errors.value[`claim_items.${index}.quantity`] = 'Quantity is required';
      }
      item.subtotal = item.unit_price * item.quantity;
    });
  
    return Object.keys(errors.value).length === 0;
  };
  
  const handleSubmit = async () => {
    
    // if (validate()) {

      try {
        const response = await axios.post('/api/claims', form);
        alert('Form submitted successfully!');

        setTimeout(() => {
            location.reload()
        }, 200);
      } catch (error) {
        
        if (error.response && error.response.data && error.response.data.errors) {
          errors.value = error.response.data.errors;
        } else {
          console.error(error);
        }
      }
    // }
  };
  </script>
  
  <style>
  .error {
    color: red;
    font-size: 0.9em;
  }
  .error-border {
    border-color: red;
  }
  .form-group {
    margin-bottom: 20px;
  }
  .item-row {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
  }
  .total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
  }
  .total {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 50%;
  }
  .btn {
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    background-color: black;
    color: white;
  }
  .btn-add {
    background-color: black;
  }
  .btn-remove {
    background-color: black;
  }
  .btn-submit {
    background-color: black;
    color: white;
    width: 50%;
  }
  </style>
  