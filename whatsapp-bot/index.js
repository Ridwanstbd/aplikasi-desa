const { Client, LocalAuth } = require("whatsapp-web.js");
const qrcode = require("qrcode-terminal");
const express = require("express");
const fs = require("fs");
const app = express();

app.use(express.json());

// Initialize WhatsApp client
const client = new Client({
    authStrategy: new LocalAuth({
        dataPath: "./sessions",
    }),
    puppeteer: {
        headless: true,
        args: [
            "--no-sandbox",
            "--disable-setuid-sandbox",
            "--disable-dev-shm-usage",
            "--disable-accelerated-2d-canvas",
            "--no-first-run",
            "--no-zygote",
            "--disable-gpu",
        ],
        dumpio: false,
    },
});

let qr = "";
let isReady = false;

// Generate QR Code
client.on("qr", (qrCode) => {
    qr = qrCode;
    console.log("QR RECEIVED");
    qrcode.generate(qrCode, { small: true });
});

// Client Ready
client.on("ready", () => {
    isReady = true;
    console.log("Client is ready!");
});

// Handle disconnected event
client.on("disconnected", async (reason) => {
    isReady = false;
    console.log("WhatsApp disconnected:", reason);
    console.log("Attempting to reconnect...");

    try {
        await client.destroy(); // Hancurkan instance klien
        await client.initialize(); // Inisialisasi ulang
    } catch (error) {
        console.error("Error during reconnection:", error);
    }
});

// Handle initialization errors
(async () => {
    try {
        await client.initialize();
    } catch (error) {
        console.error("Error during initialization:", error);
    }
})();

// API Endpoints
app.get("/status", (req, res) => {
    res.json({
        status: isReady ? "READY" : "NOT_READY",
        qr: isReady ? null : qr,
    });
});

app.post("/send-message", async (req, res) => {
    try {
        const { phone, message } = req.body;

        if (!isReady) {
            return res.status(500).json({
                success: false,
                message: "WhatsApp client not ready",
            });
        }

        // Format phone number
        let formattedPhone = phone.replace(/\D/g, "");
        if (!formattedPhone.endsWith("@c.us")) {
            formattedPhone = `${formattedPhone}@c.us`;
        }

        // Send message
        await client.sendMessage(formattedPhone, message);

        res.json({
            success: true,
            message: "Message sent successfully",
        });
    } catch (error) {
        console.error("Error sending message:", error);
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
});

// Start server
const PORT = process.env.WHATSAPP_BOT_PORT || 3000;
app.listen(PORT, () => {
    console.log(`WhatsApp bot server running on port ${PORT}`);
});

// Handle uncaught exceptions
process.on("uncaughtException", (error) => {
    console.error("Uncaught Exception:", error);
    // Restart or log the error as needed
});

// Handle unhandled promise rejections
process.on("unhandledRejection", (reason, promise) => {
    console.error("Unhandled Rejection at:", promise, "reason:", reason);
    // Log or handle the rejection as needed
});
