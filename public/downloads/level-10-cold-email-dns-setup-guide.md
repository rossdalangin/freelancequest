# FREELANCEQUEST MASTER RESOURCE VAULT
## LEVEL 10: COLD EMAIL INFRASTRUCTURE & DNS SETUP GUIDE
> **Asset Type:** MANUAL / GUIDE
> **Target Skill Level:** Level-Specific Professional Asset
> **Provided by:** FREELANCEQUEST — Play. Learn. Level Up. Get Clients. Get Paid.

---

### 1. EXECUTIVE SUMMARY & REAL-WORLD PURPOSE
Technical guide to configuring SPF, DKIM, DMARC, and secondary domain warming for high-volume cold email lead generation.

Top 5% high-earning freelancers and Virtual Assistants treat their documentation, templates, and SOPs as critical assets. By using standardized, professional frameworks, you eliminate trial-and-error, build immediate client trust, and justify charging premium retainer rates ($20–$50+/hr).

---

### 2. CORE OPERATIONAL FRAMEWORK & TEMPLATE BLUEPRINT
### COLD EMAIL INFRASTRUCTURE BLUEPRINT

#### 1. DNS Record Configuration Blueprint
To ensure cold outreach emails land in inbox (not spam), configure these DNS records on secondary domain:

```text
RECORD 1: SPF (TXT Record)
Name: @
Value: v=spf1 include:_spf.google.com ~all

RECORD 2: DKIM (TXT Record)
Name: google._domainkey
Value: [Insert Google Workspace DKIM Key]

RECORD 3: DMARC (TXT Record)
Name: _dmarc
Value: v=DMARC1; p=none; rua=mailto:dmarc-reports@yourdomain.com
```

#### 2. Domain Warmup SOP
- Week 1: 5-10 emails/day (Warmup tool active)
- Week 2: 15-25 emails/day
- Week 3: 35-50 emails/day (Ready for campaign launch)

---

### 3. COPY-PASTE CLIENT COMMUNICATION & DELIVERY SCRIPTS
#### Client Technical DNS Handover Script
```text
Subject: [TECHNICAL COMPLETE] Cold Email DNS Setup & Warmup Active

Hi [Client Name],

I have completed the technical setup for your secondary outreach domain ([Domain Name]):

✅ SPF, DKIM, and DMARC records verified.
✅ Domain warmup sequence initiated (Instantly.ai / Smartlead).
✅ Daily send limits configured to maintain 99%+ deliverability.

Warmup will run for 14 days before launching active outbound campaigns.

Best regards,
[Your Name]
```

---

### 4. ACTIVE PRACTICE MISSION & ACTION CHALLENGE
**Action Challenge (15 Mins):** Check your current domain's SPF and DMARC status using MXToolbox free online checker.

---

### 5. COMMON PITFALLS & QUALITY ASSURANCE CHECKLIST
- *Sending cold emails from primary company domain:* Always use a secondary domain to protect main business email.

---
*© FREELANCEQUEST. All Rights Reserved. Verified Career Progression Asset.*
