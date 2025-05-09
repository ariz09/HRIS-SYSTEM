// public/js/form-options.js
export const formOptions = {
  years: Array.from({length: 50}, (_, i) => new Date().getFullYear() - i),
  relationships: [
      'Spouse',
      'Parent',
      'Child',
      'Sibling',
      'Friend',
      'Other'
  ],
  dependentRelationships: [
      'Spouse',
      'Child',
      'Parent',
      'Other Dependent'
  ]
};