import json
import numpy as np
from sklearn.cluster import KMeans
import joblib

# Rule-based

def calculate_emission(data):
    meal_factors = {
        'beef': 27, 'cheese': 13.5, 'chicken': 6.9,
        'rice': 2.7, 'vegetables': 2.0, 'lentils': 0.9
    }
    vehicle_factors = {
        'petrol': 0.25, 'diesel': 0.3, 'electric': 0.2,
        'bus': 0.1, 'train': 0.04
    }

    meal = data['meal_kg'] * meal_factors.get(data['meal_type'], 2.5)
    electricity = data['electricity_kwh'] * 0.5
    vehicle = data['distance_km'] * vehicle_factors.get(data['vehicle_type'], 0.2)
    plastic = data['plastic_kg'] * 6

    return round(meal + electricity + vehicle, 2), [meal, electricity, vehicle, plastic]

# Rule-based AI Response

def rule_based_advice(emissions):
    tips = []
    if emissions['meal'] > 500:
        tips.append("🥩 Try reducing beef or switch to lentils.")
    elif emissions['meal'] > 300:
        tips.append("🍽️ Eat more vegetables or chicken to cut CO₂.")
    else:
        tips.append("✅ Great job on meal choices!")

    if emissions['electricity'] > 50:
        tips.append("💡 Turn off unused devices and use LED lighting.")
    else:
        tips.append("🔌 Efficient electricity usage!")

    if emissions['vehicle'] > 80:
        tips.append ("🚗 Try using public transport.")
    elif emissions['vehicle'] > 20:
        tips.append("🚌 Combine errands to reduce trips.")
    else:
        tips.append("🚶‍♂️ Excellent transport choice!")

    if emissions['plastic'] > 2:
        tips.append("🧴 Use reuable containers to reduce plastic.")
    else:
        tips.append("♻️ Great job on minimizing plastic use!")
    
    return "\n".join(tips)


# Clustering Prediction


def load_model():
    return joblib.load("model.pkl")

def predict_cluster(model, vector):
    return int(model.predict([vector])[0])

# Save User Log

def save_to_json(user_data, path='data.json'):
    try:
        with open(path, 'r') as f:
            existing = json.load(f)
    except:
        existing = []

    existing.append(user_data)
    with open(path, 'w') as f:
        json.dump(existing, f, indent=2)