<?php

return [
    // Tambahkan di array return:
'filters' => [
    'data_types' => [
        'Multivariate', 'Text', 'Image', 'Time-Series', 'Sequential', 
        'Tabular', 'Relational', 'Domain-Theory', 'Data-Generator', 'Univariate'
    ],
    'task_types' => [
        'Classification', 'Regression', 'Clustering', 
        'Causal-Discovery', 'Relational-Learning', 'Other'
    ],
    'variable_types' => [
        'Categorical', 'Integer', 'Real', 'Text', 'Binary', 'Ordinal', 'Nominal', 'DateTime'
    ],
    // ... lainnya
],
];
