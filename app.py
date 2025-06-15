from flask_cors import CORS
from flask import Flask, request, jsonify
import json
import run_prediction

app = Flask(__name__)
CORS(app)

@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        print("📥👌 Received input:", data)

        with open("user_input.json", "w") as f:
            json.dump(data, f)

        run_prediction.main()

        with open("output.json", "r") as f:
            result = json.load(f)

        print("📤✨ Sending result: ", result)
        return jsonify(result)
    
    except Exception as e:
        print("⚠️ Error:", e)
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True)