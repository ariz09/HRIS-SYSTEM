export const formOptions = {
    relationships: [
      'Spouse', 'Parent', 'Child', 'Sibling',
      'Friend', 'Relative', 'Colleague', 'Other'
    ],
    dependentRelationships: [
      'Spouse', 'Child', 'Parent', 'Sibling',
      'Grandchild', 'Other Family', 'Other'
    ],
    getYearOptions: (start = 1950, end = new Date().getFullYear() + 5) => {
      return Array.from({length: end - start + 1}, (_, i) => start + i)
    }
  }