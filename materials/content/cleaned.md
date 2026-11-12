---
title: Linear regression
provider: Google Machine Learning Crash Course
canonical_url: https://developers.google.com/machine-learning/crash-course/linear-regression
retrieved_at: 2026-10-31
source_last_updated: 2025-12-09
language: en
topic: linear-regression
content_selector: ".devsite-article-body"
license: CC-BY-4.0
code_license: Apache-2.0
extractor_version: manual-spike-v1
---

# Linear regression

## Module information

**Estimated length:** 80 minutes

### Learning objectives

- Explain a loss function and how it works.
- Define and describe how gradient descent finds the optimal model parameters.
- Describe how to tune hyperparameters to efficiently train a linear model.

### Prerequisites

- [Introduction to Machine Learning](https://developers.google.com/machine-learning/intro-to-ml)

## Introduction

Linear regression is a statistical technique used to find the relationship between variables. In an ML context, linear regression finds the relationship between features and a label.

## Example: vehicle weight and fuel efficiency

For example, suppose we want to predict a car's fuel efficiency in miles per gallon based on how heavy the car is, and we have the following dataset:

| Pounds in 1000s (feature) | Miles per gallon (label) |
|---:|---:|
| 3.5 | 18 |
| 3.69 | 15 |
| 3.44 | 18 | 
| 3.43 | 16 |
| 4.34 | 15 |
| 4.42 | 14 |
| 2.37 | 24 |

If we plotted these points, we'd get the following graph:

:::figure
id: figure-01
position: 1
original_url: https://developers.google.com/static/machine-learning/crash-course/linear-regression/images/car-data-points.png
local_path: figures/figure-01-car-data-points.png
alt_text: "Scatter plot of vehicle weight against miles per gallon, showing a downward trend as vehicle weight increases."
caption: "Car heaviness (in pounds) versus miles per gallon rating. As a car gets heavier, its miles per gallon rating generally decreases."
:::

We could create our own model by drawing a best fit line through the points:

:::figure
id: figure-02
position: 2
original_url: https://developers.google.com/static/machine-learning/crash-course/linear-regression/images/car-data-points-with-model.png
local_path: figures/figure-02-best-fit-line.png
alt_text: "Vehicle weight and miles-per-gallon data points with a downward-sloping best-fit line representing the linear model."
caption: "A best fit line drawn through the data from the previous figure."
:::

## Linear regression equation

In algebraic terms, the model is defined as $y = mx + b$, where:

$$
y = mx + b
$$

- $y$: is miles per gallon—the value we want to predict.
- $m$: is the slope of the line.
- $x$: is pounds—our input value.
- $b$: is the y-intercept.

In ML, we write the equation for a linear regression model as follows:

$$
y' = b + w_1x_1
$$

- $y'$: is the predicted label—the output.
- $b$: is the bias of the model. Bias is the same concept as the y-intercept in the algebraic equation for a line. In ML, bias is sometimes referred to as $w_0$. Bias is a parameter of the model and is calculated during training.
- $w_1$: is the weight of the feature. Weight is the same concept as the slope $m$ in the algebraic equation for a line. Weight is a parameter of the model and is calculated during training.
- $x_1$: is a feature—the input.

During training, the model calculates the weight and bias that produce the best model.

:::figure
id: figure-03
position: 3
original_url: https://developers.google.com/static/machine-learning/crash-course/linear-regression/images/equation.png
local_path: figures/figure-03-linear-equation.png
alt_text: "Annotated linear regression equation y prime equals b plus w one x one, identifying the prediction, bias, weight, and feature."
caption: "Mathematical representation of a linear model."
:::

### Example prediction

In our example, we'd calculate the weight and bias from the line we drew. The bias is 34 (where the line intersects the y-axis), and the weight is –4.6 (the slope of the line). The model would be defined as $y' = 34 + (-4.6)(x_1)$, and we could use it to make predictions. For instance, using this model, a 4,000-pound car would have a predicted fuel efficiency of 15.6 miles per gallon.

:::figure
id: figure-04
position: 4
original_url: https://developers.google.com/static/machine-learning/crash-course/linear-regression/images/model-prediction.png
local_path: figures/figure-04-model-prediction.png
alt_text: "Best-fit line with the prediction point for a four-thousand-pound vehicle highlighted at 15.6 miles per gallon."
caption: "Using the model, a 4,000-pound car has a predicted fuel efficiency of 15.6 miles per gallon."
:::

### Models with multiple features

Although the example in this section uses only one feature—the heaviness of the car—a more sophisticated model might rely on multiple features, each having a separate weight ($w_1$, $w_2$, etc.). For example, a model that relies on five features would be written as follows:

$$
y' = b + w_1x_1 + w_2x_2 + w_3x_3 + w_4x_4 + w_5x_5
$$

For example, a model that predicts gas mileage could additionally use features such as the following:

- Engine displacement
- Acceleration
- Number of cylinders
- Horsepower
 
This model would be written as follows:

:::figure
id: figure-05
position: 5
original_url: https://developers.google.com/static/machine-learning/crash-course/linear-regression/images/equation-multiple-features.png
local_path: figures/figure-05-multiple-features.png
alt_text: "Linear regression equation containing five weighted input features and one bias term."
caption: "A model with five features to predict a car's miles per gallon rating."
:::

By graphing a couple of these additional features, we can see that they also have a linear relationship to the label, miles per gallon:

:::figure
id: figure-06
position: 6
original_url: https://developers.google.com/static/machine-learning/crash-course/linear-regression/images/displacement.png
local_path: figures/figure-06-displacement.png
alt_text: "Scatter plot of engine displacement against miles per gallon, showing a negative linear relationship."
caption: "A car's displacement in cubic centimeters and its miles per gallon rating. As a car's engine gets bigger, its miles per gallon rating generally decreases."
:::

:::figure
id: figure-07
position: 7
original_url: https://developers.google.com/static/machine-learning/crash-course/linear-regression/images/acceleration.png
local_path: figures/figure-07-acceleration.png
alt_text: "Scatter plot of acceleration time against miles per gallon, showing a positive linear relationship"
caption: "A car's acceleration and its miles per gallon rating. As a car's acceleration takes longer, the miles per gallon rating generally increases."
:::

### Exercise: Check your understanding

:::exercise
question: "What parts of the linear regression equation are updated during training?"

choices:
  - text: "The bias and weights"
    correct: true
    explanation: "During training, the model updates the bias and weights."
  - text: "The prediction"
    correct: false
    explanation: "Predictions are not updated during training."
  - text: "The feature values"
    correct: false
    explanation: "Feature values are part of the dataset, so they're not updated during training."
:::

## Key terms

- [Bias](https://developers.google.com/machine-learning/glossary#bias-math-or-bias-term)
- [Feature](https://developers.google.com/machine-learning/glossary#feature)
- [Label](https://developers.google.com/machine-learning/glossary#label)
- [Linear regression](https://developers.google.com/machine-learning/glossary#linear-regression)
- [Parameter](https://developers.google.com/machine-learning/glossary#parameter)
- [Weight](https://developers.google.com/machine-learning/glossary#weight)